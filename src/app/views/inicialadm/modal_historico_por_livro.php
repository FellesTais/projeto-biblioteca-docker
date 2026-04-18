<div class="modal fade" id="modalHistoricoPorLivro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Histórico por livro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscaHistorico" class="d-flex align-items-center mb-3" onsubmit="return false;">
          <input type="text" id="inputLivro" class="form-control me-2" placeholder="Informe o título do livro" required>
          <button type="button" class="btn btn-secondary" onclick="buscarHistoricoPorLivro()">Pesquisar</button>
        </form>
        <div id="listaHistoricoPorLivro">
        </div>
      </div>
    </div>
  </div>
</div>
