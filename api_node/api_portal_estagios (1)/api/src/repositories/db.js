const mysql = require('mysql2/promise');

// Pool de conexoes — cria conexoes reutilizaveis automaticamente
// As variaveis vem do .env carregado no index.js
const pool = mysql.createPool({
  host:     process.env.DB_HOST,
  user:     process.env.DB_USER,
  password: process.env.DB_PASS,
  database: process.env.DB_NAME,
});

module.exports = pool;
