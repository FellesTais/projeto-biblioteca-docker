<div class="modal fade" id="modalCadastroCategoria" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="fw-bold">Cadastrar Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form id="formCadastroCategoria" action="../index.php?action=cadastrarCategoria">
        <div class="modal-body form-cadastrar-categoria">
          <input type="text" name="novaCategoria" placeholder="Categoria" required>
          <div class="botoes-categoria">
            <button type="submit" class="btn btn-secondary">Confirmar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
