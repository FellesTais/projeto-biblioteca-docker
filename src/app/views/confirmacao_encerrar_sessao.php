<div class="modal fade" id="modalEncerrarSessao" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <p class="texto-mensagem-confirmacao mb-2">Deseja encerrar a sessão?</p>
      </div>
      <form class="modal-footer d-flex justify-content-center border-0 p-0 mb-2" action="../index.php?view=encerrarSessao" method="GET">
        <input type="hidden" name="view" value="encerrarSessao" />
        <button type="submit" class="btn btn-danger">Sim</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
      </form>
    </div>
  </div>
</div>