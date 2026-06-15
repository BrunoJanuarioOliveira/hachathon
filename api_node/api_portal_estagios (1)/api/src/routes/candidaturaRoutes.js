const express = require('express');
const router  = express.Router();
const candidaturaCtrl = require('../controllers/candidaturaController');

router.get('/',       candidaturaCtrl.listar);         // GET   /api/candidaturas?aluno_id=X&vaga_id=Y
router.post('/',      candidaturaCtrl.criar);           // POST  /api/candidaturas
router.patch('/:id',  candidaturaCtrl.atualizarStatus); // PATCH /api/candidaturas/:id

module.exports = router;
