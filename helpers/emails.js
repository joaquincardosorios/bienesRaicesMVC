import nodemailer from 'nodemailer'

const emailRegistro = async (datos) => {
    const transport = nodemailer.createTransport({
        host: process.env.EMAIL_HOST,
        port: process.env.EMAIL_PORT,
        auth: {
          user: process.env.EMAIL_USER,
          pass: process.env.EMAIL_PASS
        }
    });

    const { email, nombre, token} = datos

    //Enviar email
    await transport.sendMail({
        from: 'BienesRaices.com',
        to:email,
        subject: 'Confirma tu Cuenta en BienesRaices.com',
        text: 'Confirma tu Cuenta en BienesRaices.com',
        html: `
            <p> Hola ${nombre}, comprueba tu cuenta en bienesRaices.com </p>
            <p> Tu cuenta esta lista, solo debes confirmarla en el siguiente enlace: </p>
            <a href="${process.env.BACKEND_URL}:${process.env.BACKEND_PORT || 3000}/auth/confirmar/${token}">Confirma tu cuenta</a>

            <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>
        `
    })
}

const emailOlvidePassword = async (datos) => {
    const transport = nodemailer.createTransport({
        host: process.env.EMAIL_HOST,
        port: process.env.EMAIL_PORT,
        auth: {
          user: process.env.EMAIL_USER,
          pass: process.env.EMAIL_PASS
        }
    });

    const { email, nombre, token} = datos

    //Enviar email
    await transport.sendMail({
        from: 'BienesRaices.com',
        to:email,
        subject: 'Reestablece tu password en BienesRaices.com',
        text: 'Reestablece tu password en BienesRaices.com',
        html: `
            <p> Hola ${nombre}, haz solicitado reestablecer tu password en bienesRaices.com </p>
            <p> Sigue el siguiente el siguiente enlace para generar un password nuevo: </p>
            <a href="${process.env.BACKEND_URL}:${process.env.BACKEND_PORT || 3000}/auth/recuperar-password/${token}">Reestablecer tu password</a>

            <p>Si tu no solcitaste este cambio de password, puedes ignorar este mensaje</p>
        `
    })
}


export{
    emailRegistro,
    emailOlvidePassword
}