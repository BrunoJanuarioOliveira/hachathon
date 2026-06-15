const express = require('express');
const router  = express.Router();
const vagaCtrl = require('../controllers/vagaController');

router.get('/',    vagaCtrl.listar);   // GET    /api/vagas
router.post('/',   vagaCtrl.criar);    // POST   /api/vagas
router.put('/:id', vagaCtrl.atualizar); // PUT    /api/vagas/:id
router.delete('/:id', vagaCtrl.deletar); // DELETE /api/vagas/:id

module.exports = router;
