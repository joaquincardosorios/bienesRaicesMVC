import express from 'express'

const router = express.Router()

// Routing
router.get('/', (req,res) => {
    res.send('Hola Mundo')
})

router.get('/nosotros', (req,res) => {
    res.send('Informacion de Nosotros')
})

export default router