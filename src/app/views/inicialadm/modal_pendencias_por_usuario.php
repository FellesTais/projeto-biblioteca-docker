<div class="modal fade" id="modalPendenciasPorUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Pendências por usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscaPendenciasAdm" class="d-flex align-items-center mb-3" onsubmit="return false;">
          <input type="text" id="inputUsuarioPendenciasAdm" class="form-control me-2" placeholder="Informe o usuário" required>
          <button type="button" class="btn btn-secondary" onclick="buscarPendenciasPorUsuarioAdm()">Pesquisar</button>
        </form>
        <div id="listaPendenciasPorUsuarioAdm">
        </div>
      </div>
    </div>
  </div>
</div>
