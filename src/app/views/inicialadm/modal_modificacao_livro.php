<div class="modal fade" id="modalModificacaoLivro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="fw-bold">Modificar Livro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <form id="formModificarLivro" action="../index.php?action=modificarLivro" method="POST" enctype="multipart/form-data">
        <div class="modal-body campos-cadastro-livro px-2">

          <div class="row g-4 mb-2 justify-content-center">
            <div class="col-md-5">
              <h6 class="mb-1">Título:</h6>
              <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="col-md-5">
              <h6 class="mb-1">Autor:</h6>
              <input type="text" name="autor" class="form-control" required>
            </div>
          </div>

          <div class="row g-4 mb-2 justify-content-center">
            <div class="col-md-5">
              <h6 class="mb-1">Data de Lançamento:</h6>
              <input type="date" name="data_lancamento" class="form-control" required>
            </div>
            <div class="col-md-5">
              <h6 class="mb-1">Categoria:</h6>
              <select name="categoria" class="form-control" required>
                <option value="" disabled selected>Selecione uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['categoria']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row g-4 mb-2 justify-content-center">
            <div class="col-md-5">
              <h6 class="mb-1">Sinopse:</h6>
              <textarea name="sinopse" rows="4" class="form-control" required></textarea>
            </div>
            <div class="col-md-5">
              <h6 class="mb-1">Imagem de Capa:</h6>
              <img id="imgCapaAtual" src="" alt="Capa Atual" class="img-fluid mb-2" style="max-height: 150px; display:none; border: 1px solid #ccc; padding: 5px;">
              <input type="file" name="capa" class="form-control" accept="image/*">
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary">Confirmar</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
