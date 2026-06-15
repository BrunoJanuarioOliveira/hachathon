const express = require('express');
const router  = express.Router();
const empresaCtrl = require('../controllers/empresaController');

router.get('/',              empresaCtrl.listar);          // GET   /api/empresas
router.post('/',             empresaCtrl.criar);            // POST  /api/empresas
router.patch('/:id/status',  empresaCtrl.atualizarStatus); // PATCH /api/empresas/:id/status

module.exports = router;
