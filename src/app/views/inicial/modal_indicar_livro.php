<div class="modal fade" id="indicarLivro" tabindex="-1" aria-labelledby="modalLivroLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold">Indicar Livro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div>
                <form action="../index.php?action=indicarLivro" method="POST">
                    <div class="modal-body form-indicar-livro" id="modalLivroBodyIndicar">
                        <input type="text" name="tituloIndicacao" placeholder="Título" required>
                        <input type="text" name="autorIndicacao" placeholder="Autor" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary">Confirmar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>