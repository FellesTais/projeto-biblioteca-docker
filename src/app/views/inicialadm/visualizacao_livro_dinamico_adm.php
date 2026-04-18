<?php if (!empty($livro)): ?>
    <span class="badge bg-secondary mt-0"><?= htmlspecialchars($livro['nome_categoria']) ?></span>
    <span id="badge-status-reserva"></span>

  <div class="imagem-container position-relative mb-3 mt-1">
    <img src="<?= !empty($livro['capa']) ? htmlspecialchars($livro['capa']) : 'https://via.placeholder.com/250x150' ?>"
      class="card-img-top rounded-3 my-2 mb-0 visualizacao-capa-livro" alt="Imagem do livro" id="imagemCapaLivro">

    <button class="btn btn-light btn-sm btn-expandir-imagem" id="btnExpandirImagem" type="button"
      aria-label="Expandir imagem">
      <i class="bi bi-arrows-fullscreen"></i>
    </button>
  </div>

  <p class="mb-0 text-muted d-flex justify-content-between">
    <span>Autor: <strong><?= htmlspecialchars($livro['autor']) ?></strong></span>
    <span>Lançamento:
      <strong><?= !empty($livro['data_lancamento']) ? date('d/m/Y', strtotime($livro['data_lancamento'])) : 'N/A' ?></strong></span>
  </p>
  <p class="sinopse-visualizacao-livro"><?= htmlspecialchars($livro['sinopse']) ?></p>
<?php else: ?>
  <p class="text-danger">Livro não encontrado.</p>
<?php endif; ?>