<div class="modal fade" id="modalLivro" tabindex="-1" aria-labelledby="modalLivroLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0" id="modalLivroLabel">Título do Livro</h5>
        <div class="ms-auto d-flex align-items-center gap-1">
          <span id="botao-favoritar" class="d-inline-block me-1"></span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
      </div>
      <div class="modal-body" id="modalLivroBody">

      </div>
      <div class="modal-footer" id="modalLivroFooter">
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

<div class="modal fade" id="modalComentarios" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold mb-0">Comentários</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formComentario" class="d-flex gap-2 align-items-center mb-3">
          <input type="hidden" name="idLivro" id="idLivroComentario">
          <input type="text" name="comentario" id="comentario" placeholder="Escreva aqui seu comentário..." class="form-control" required>
          <button type="submit" class="btn btn-secondary">Enviar</button>
        </form>
        <div class="list-group" id="listaComentarios"></div>
      </div>
    </div>
  </div>
</div>