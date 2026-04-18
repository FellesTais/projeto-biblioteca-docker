<div class="modal fade" id="modalPendenciasPorLivro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Pendências por livro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formBuscaPendenciasPorLivroAdm" class="d-flex align-items-center mb-3" onsubmit="return false;">
          <input type="text" id="inputLivroPendenciasAdm" class="form-control me-2" placeholder="Informe o título do livro" required>
          <button type="button" class="btn btn-secondary" onclick="buscarPendenciasPorLivroAdm()">Pesquisar</button>
        </form>
        <div id="listaPendenciasPorLivroAdm">
        </div>
      </div>
    </div>
  </div>
</div>
