<div class="modal fade" id="modalHistoricoPorUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Histórico por usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscaHistorico" class="d-flex align-items-center mb-3" onsubmit="return false;">
          <input type="text" id="inputUsuario" class="form-control me-2" placeholder="Informe o usuário" required>
          <button type="button" class="btn btn-secondary" onclick="buscarHistoricoPorUsuario()">Pesquisar</button>
        </form>
        <div id="listaHistorico">
        </div>
      </div>
    </div>
  </div>
</div>
