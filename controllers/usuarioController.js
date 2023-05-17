import { check, validationResult } from 'express-validator'
import bcrypt from 'bcrypt'
import Usuario from "../models/Usuario.js"
import { generarId } from "../helpers/tokens.js"
import { emailRegistro, emailOlvidePassword } from '../helpers/emails.js'


const formularioLogin = (req,res) => {
    res.render('auth/login',{
        pagina: 'Iniciar Sesion'
    })
}

const formularioRegistro = (req,res) => {

    
    res.render('auth/registro',{
        pagina: 'Crear Cuenta',
        csrfToken: req.csrfToken()
    })
}

const registrar = async (req,res) => {
    
    // Validacion
    await check('nombre').notEmpty().withMessage('El nombre es obligatorio').run(req)
    await check('email').isEmail().withMessage('Eso no parece un email').run(req)
    await check('password').isLength({min: 6}).withMessage('Es password debe ser de al menos 6 caracteres').run(req)
    await check('repetir_password').equals(req.body.password).withMessage('Los passwords no son iguales').run(req)

    

    let resultado = validationResult(req)

    // Verifica que el resultado este vacio
    if (!resultado.isEmpty()){
        return res.render('auth/registro',{
            pagina: 'Crear Cuenta',
            csrfToken: req.csrfToken(),
            errores: resultado.array(),
            usuario:{
                nombre: req.body.nombre,
                email: req.body.email
            }
        })
    }

    // Extraer los datos
    const {nombre, email, password} = req.body

    // Verifica que usuario no existe anteriormente
    const existeUsuario = await Usuario.findOne({ where: { email }})

    if (existeUsuario) {
        return res.render('auth/registro',{
            pagina: 'Crear Cuenta',
            csrfToken: req.csrfToken(),
            errores: [ {msg: 'El usuario ya existe.'}],
            usuario:{
                nombre: req.body.nombre,
                email: req.body.email
            }
        })
    }

    // Almacenar usuario
    const usuario = await Usuario.create({
        nombre,
        email,
        password,
        token: generarId()
    })

    //Envia email de confirmacion
    emailRegistro({
        nombre: usuario.nombre, 
        email: usuario.email, 
        token: usuario.token
    })

    // Mostrar mensaje de confirmacion
    res.render('templates/mensaje',{
        pagina: "Cuenta Creada Correctamente",
        mensaje: 'Hemos enviado un Email de Confirmación, presiona en el enlace'
    })

}

// Funcion que comprueba una cuenta
const confirmar = async (req,res, next) => {
const { token } = req.params


    // Verificar si el token es valido
    const usuario = await Usuario.findOne({ where: {token}})

    if(!usuario){
        return res.render('auth/confirmar-cuenta', {
            pagina: "Error al confirmar tu Cuenta",
            mensaje: 'Hubo un error al confirmar tu cuenta, prueba de nuevo',
            error: true
        })
    }

    // Confirmar la cuenta
    usuario.token= null
    usuario.confirmado = true
    await usuario.save();

    return res.render('auth/confirmar-cuenta', {
        pagina: "Cuenta Confirmada",
        mensaje: 'La Cuenta se confirmó correctamente'
    })


    
 }

const formularioRecuperarPassword = (req,res) => {
    res.render('auth/recuperar-password',{
        pagina: 'Recupera tu acceso a Bienes Raices',
        csrfToken: req.csrfToken()
    })
}


const resetPassword =async (req,res) => {
    // Validacion
    await check('email').isEmail().withMessage('Eso no parece un email').run(req)

    let resultado = validationResult(req)

    // Verifica que el resultado este vacio
    if (!resultado.isEmpty()){
        return res.render('auth/recuperar-password',{
            pagina: 'Recupera tu acceso a Bienes Raices',
            csrfToken: req.csrfToken(),
            errores: resultado.array(),
        })
    }

    // Buscar al usuario
    const {email} = req.body
    const usuario = await Usuario.findOne({ where: { email }})
    if(!usuario){
        return res.render('auth/recuperar-password',{
            pagina: 'Recupera tu acceso a Bienes Raices',
            csrfToken: req.csrfToken(),
            errores: [ {msg: 'El usuario no existe.'}],
        })
    }

    // Generar nuevo Token
    usuario.token=generarId();
    await usuario.save()

    // Enviar email
    await emailOlvidePassword({
        email:usuario.email,
        nombre:usuario.nombre,
        token:usuario.token
    })

    // Renderizar un mensaje
    res.render('templates/mensaje',{
        pagina: "Reestablece tu Password",
        mensaje: 'Hemos enviado un Email con las instrucciones'
    })
}

const comprobarToken= async (req,res) => {
    const {token} = req.params
    const usuario = await Usuario.findOne({ where: { token }})

    if(!usuario){
        return res.render('auth/confirmar-cuenta', {
            pagina: "Reestablece tu password",
            mensaje: 'Hubo un error al validar tu información, prueba de nuevo',
            error: true
        })
    }

    // Mostrar formulario para modificar password
    res.render('auth/reset-password',{
        pagina: 'Reestablece tu Password',
        csrfToken: req.csrfToken(),
    })
}
const nuevoPassword=async (req,res) => {
    // Validacion
    await check('password').isLength({min: 6}).withMessage('Es password debe ser de al menos 6 caracteres').run(req)
    await check('repetir_password').equals(req.body.password).withMessage('Los passwords no son iguales').run(req)

    let resultado = validationResult(req)

    // Verifica que el resultado este vacio
    if (!resultado.isEmpty()){
        return res.render('auth/reset-password',{
            pagina: 'Reestablece tu Password',
            csrfToken: req.csrfToken(),
            errores: resultado.array()
        })
    }

    const {token} = req.params
    const {password} = req.body
    // Identifica quien hace el cambio
    const usuario = await Usuario.findOne({ where: { token }})

    // Hashear password
    const salt = await bcrypt.genSalt(10)
    usuario.password = await bcrypt.hash(password, salt)
    usuario.token = null

    await usuario.save()

    res.render('auth/confirmar-cuenta',{
        pagina: 'Password Reestablecido',
        mensaje: 'El password se guardó correctamente'
    })

}

export {
    formularioLogin,
    formularioRegistro,
    registrar,
    confirmar,
    formularioRecuperarPassword,
    resetPassword,
    comprobarToken,
    nuevoPassword
}