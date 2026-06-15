const express = require('express');
const router  = express.Router();
const alunoCtrl = require('../controllers/alunoController');

router.get('/',     alunoCtrl.listar);   // GET  /api/alunos
router.get('/:id',  alunoCtrl.buscar);   // GET  /api/alunos/:id
router.post('/',    alunoCtrl.criar);    // POST /api/alunos
router.put('/:id',  alunoCtrl.atualizar); // PUT  /api/alunos/:id

module.exports = router;
