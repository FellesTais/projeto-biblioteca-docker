<div class="modal fade" id="modalLivroAdm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0" id="modalLivroLabelAdm">Título do Livro</h5>
        <div class="ms-auto d-flex align-items-center gap-1">
          <i class="bi bi-info-circle fs-5 text-primary me-2" id="btnInformacoes" style="cursor: pointer;"></i>
          <i class="bi bi-pencil fs-5 text-dark me-2" id="btnModificarLivro" style="cursor: pointer;"></i>
          <i class="bi bi-trash fs-5 text-dark me-2" id="btnExcluirLivro" style="cursor: pointer;"></i>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
      </div>
      <div class="modal-body" id="modalLivroBodyAdm">

      </div>
      <div class="modal-footer" id="modalLivroFooterAdm">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalImagemExpandida" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content bg-dark border-0">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      <div class="modal-body p-0 text-center">
        <img id="imagemExpandida" src="" class="w-100" style="max-height: 90vh; object-fit: contain;"
          alt="Imagem expandida">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalComentariosAdm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Comentários</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="list-group" id="listaComentarios"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalInformacoesAdm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Informações reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-1"><strong>Usuário:</strong> <span id="infoUsuario">...</span></p>
        <p class="mb-1"><strong>Data retirada:</strong> <span id="infoRetirada">...</span></p>
        <p class="mb-0"><strong>Data devolução:</strong> <span id="infoDevolucao">...</span></p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalExcluirLivro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p class="texto-mensagem-confirmacao mb-2 fs-5">Deseja excluir o livro?</p>
      </div>
      <div class="modal-footer d-flex justify-content-center border-0 p-0 mb-2">
        <button id="btnConfirmarExclusao" class="btn btn-danger">Sim</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
      </div>
    </div>
  </div>
</div>