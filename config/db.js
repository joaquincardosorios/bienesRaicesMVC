import Sequelize from 'sequelize'
import dotenv from 'dotenv'
dotenv.config({path: '.env'})

const db = new Sequelize(process.env.DB_NOMBRE, process.env.DB_USER,process.env.DB_PASS,{
    host: process.env.DB_HOST,
    port: '5433',
    dialect: 'postgres',
    define: {
        timestamps: true
    },
    pool: {
        max: 5,
        min: 5,
        acquire: 30000,
        idle: 10000
    },
    operatorAliases: false
});

export default db