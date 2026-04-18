// Login
/*let signInBtn = document.querySelector("#sign_in_btn");
let signUpBtn = document.querySelector("#sign_up_btn");
let signInForm = document.querySelector(".sign_in_form");
let body = document.querySelector("body");

signUpBtn.onclick = function () {
  body.classList.add("form_slide");
  if (body.classList.contains("form_slide")) {
    body.style.backgroundColor = '#3E2723';
  }
}

signInBtn.onclick = function () {
  body.classList.remove("form_slide");
  body.style.backgroundColor = '#003C47';
}
*/

document.addEventListener('DOMContentLoaded', function () {
  verificarNotificacoes();
});


// Mensagem
function exibirToast(mensagem, tipo = 'sucesso') {
  const toastClass = tipo === 'sucesso' ? 'bg-success text-white' : 'bg-danger text-white';

  const toastContainer = document.createElement('div');
  toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
  toastContainer.style.zIndex = '1055';
  toastContainer.innerHTML = `
    <div class="toast ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
      <div class="toast-body d-flex justify-content-between align-items-center">
        <span>${mensagem}</span>
        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Fechar"></button>
      </div>
    </div>
  `;
  document.body.appendChild(toastContainer);

  const toastEl = toastContainer.querySelector('.toast');
  const bsToast = new bootstrap.Toast(toastEl);
  bsToast.show();

  toastEl.addEventListener('hidden.bs.toast', () => {
    toastContainer.remove();
  });
}

// Guias
function configurarGuias(idContainer) {
  const container = document.getElementById(idContainer);
  if (!container) return;

  const links = container.querySelectorAll('.nav-link');
  const livrosContainer = document.getElementById('livrosContainer');

  const inputPesquisa = document.querySelector('.input-group input');
  const botaoPesquisa = document.querySelector('.input-group button');
  const itensCategoria = document.querySelectorAll('.categoria-item');

  let guiaAtual = 'todos';
  let categoriaSelecionada = '';
  let pesquisaAtual = '';

  async function carregarLivros(guia, pagina = 1) {
    try {
      const res = await fetch(`/index.php?action=configurarGuiasLivros&guia=${guia}&pagina=${pagina}&categoria=${encodeURIComponent(categoriaSelecionada)}&pesquisa=${encodeURIComponent(pesquisaAtual)}`);
      if (!res.ok) throw new Error('Erro ao buscar livros');

      const data = await res.json();
      const { livros, total, limite } = data;

      if (livros.length === 0) {
        livrosContainer.innerHTML = `<p class="text-light text-center">Nenhum livro encontrado</p>`;
        return;
      }

      let html = '<div class="row g-4">';
      livros.forEach(livro => {
        html += `
          <div class="col-sm-6 col-lg-3">
            <a onclick="abrirVisualizacaoLivro(this)" data-id="${livro.id}" data-titulo="${livro.titulo}">
              <div class="card rounded-4 shadow-sm h-100 d-flex flex-column exibicao-livro-card">
                <div class="exibicao-livro-titulo">
                  <h5 class="card-title fw-bold mb-0">${livro.titulo}</h5>
                </div>
                <img src="${livro.capa || 'https://via.placeholder.com/250x150'}"
                     class="card-img-top rounded-3 mt-1 mb-0 exibicao-livro-capa" alt="Imagem do livro">
                <div class="text-center mt-1">
                  <p class="mb-0 text-muted small"><strong>${livro.autor}</strong></p>
                  <span class="badge bg-secondary">${livro.nome_categoria}</span>
                </div>
              </div>
            </a>
          </div>`;
      });
      html += '</div>';

      const totalPaginas = Math.ceil(total / limite);
      if (totalPaginas >= 1) {
        html += '<div class="d-flex justify-content-center mt-4 flex-wrap gap-2 align-items-center">';

        html += `
          <button type="button" class="btn btn-sm btn-outline-secondary" 
            ${pagina === 1 ? 'disabled' : ''}
            onclick="carregarLivros('${guia}', ${pagina - 1})">
            &laquo;
          </button>
        `;
        for (let i = 1; i <= totalPaginas; i++) {
          html += `
            <button type="button" class="btn btn-sm ${i === pagina ? 'btn-secondary' : 'btn-outline-secondary'}"
              onclick="carregarLivros('${guia}', ${i})">
              ${i}
            </button>
          `;
        }

        html += `
          <button type="button" class="btn btn-sm btn-outline-secondary" 
            ${pagina === totalPaginas ? 'disabled' : ''}
            onclick="carregarLivros('${guia}', ${pagina + 1})">
            &raquo;
          </button>
        `;

        html += '</div>';
      }

      livrosContainer.innerHTML = html;
    } catch (error) {
      livrosContainer.innerHTML = '<p class="text-danger">Erro ao carregar livros.</p>';
      console.error(error);
    }
  }

  links.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      if (this.classList.contains('disabled')) return;

      links.forEach(tab => tab.classList.remove('active'));
      this.classList.add('active');

      guiaAtual = this.getAttribute('data-tab');
      carregarLivros(guiaAtual, 1);
    });
  });

  itensCategoria.forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      categoriaSelecionada = item.dataset.id || '';
      carregarLivros(guiaAtual, 1);
    });
  });

  botaoPesquisa.addEventListener('click', () => {
    pesquisaAtual = inputPesquisa.value.trim();
    carregarLivros(guiaAtual, 1);
  });

  inputPesquisa.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      pesquisaAtual = inputPesquisa.value.trim();
      carregarLivros(guiaAtual, 1);
    }
  });

  carregarLivros(guiaAtual, 1);

  window.carregarLivros = carregarLivros;
}



// Modais
function configurarBotaoFavoritar() {
  const botaoFavoritar = document.getElementById('botao-favoritar');
  if (!botaoFavoritar) return;

  const livroId = botaoFavoritar.getAttribute('data-id');
  if (!livroId) return;

  let favoritado = botaoFavoritar.getAttribute('data-favorito') === '1';

  function renderBotaoFavorito() {
    botaoFavoritar.innerHTML = `
      <button type="button" class="btn p-0" id="btnFavorito" aria-label="Favoritar">
        <i class="bi ${favoritado ? 'bi-heart-fill' : 'bi-heart'} fs-4"></i>
      </button>
    `;
  }

  // Render inicial
  renderBotaoFavorito();

  // Evento de clique no botão
  botaoFavoritar.onclick = function (e) {
    if (e.target.closest('#btnFavorito')) {
      favoritado = !favoritado;
      renderBotaoFavorito();

      fetch('index.php?action=favoritarLivro', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + encodeURIComponent(livroId)
      })
        .then(response => response.text())
        .then(data => {
          console.log('Resposta do servidor:', data);
          botaoFavoritar.setAttribute('data-favorito', favoritado ? '1' : '0');
        })
        .catch(error => {
          console.error('Erro ao favoritar livro:', error);
        });
    }
  };
}

function atualizarIconeFavorito(idLivro) {
  fetch(`../index.php?action=verificaFavorito&id=${idLivro}`)
    .then(res => res.json())
    .then(data => {
      const favorito = data.favorito;
      const icone = document.querySelector('#botao-favoritar i');
      const span = document.getElementById('botao-favoritar');
      span.dataset.idLivro = idLivro;
      if (favorito == 1) {
        icone.classList.remove('bi-heart');
        icone.classList.add('bi-heart-fill', 'text-danger');
      } else {
        icone.classList.remove('bi-heart-fill', 'text-danger');
        icone.classList.add('bi-heart');
      }
    });
}

function renderBotoesConformeReserva(id) {
  fetch(`index.php?action=verificarDisponibilidadeLivro&id=${id}`)
    .then(res => res.json())
    .then(json => {
      const { statusDisponibilidade, reservaAtiva, dataDevolucao } = json;

      const modalFooter = document.getElementById('modalLivroFooter');
      if (!modalFooter) return;

      modalFooter.innerHTML = '';

      if (statusDisponibilidade === 0) {
        const btnRetirar = document.createElement('button');
        btnRetirar.id = 'btnRetirarLivro';
        btnRetirar.className = 'btn btn-secondary me-1';
        btnRetirar.textContent = 'Retirar';
        btnRetirar.onclick = () => solicitarReservaLivro(id);
        modalFooter.appendChild(btnRetirar);
      } else if (statusDisponibilidade === 1 && reservaAtiva == true) {
        const btnDevolver = document.createElement('button');
        btnDevolver.className = 'btn btn-secondary me-1';
        btnDevolver.textContent = 'Devolver';
        btnDevolver.onclick = () => solicitarDevolucaoLivro(id);
        modalFooter.appendChild(btnDevolver);

        const modalBody = document.getElementById('modalLivroBody');
        if (modalBody && dataDevolucao) {
          const info = document.createElement('div');
          info.id = 'texto-data-devolucao';

          const DateTime = luxon.DateTime;

          const devolucaoDate = DateTime.fromISO(dataDevolucao, { zone: 'America/Sao_Paulo' }).startOf('day');
          const hoje = DateTime.now().setZone('America/Sao_Paulo').startOf('day');

          const diffDias = hoje.diff(devolucaoDate, 'days').days;

          if (diffDias >= 1) {
            info.textContent = 'Atrasado';
            info.classList.add('devolucao-atrasada');
          } else {
            info.textContent = `Devolução até ${devolucaoDate.toFormat('dd/LL/yyyy')}`;
            info.classList.add('devolucao-em-dia');
          }

          modalBody.appendChild(info);
        }

      } else {
        const btnRetirar = document.createElement('button');
        btnRetirar.className = 'btn btn-secondary me-1 disabled';
        btnRetirar.textContent = 'Retirar';
        btnRetirar.disabled = true;
        modalFooter.appendChild(btnRetirar);
      }

      const botaoComentarios = document.createElement('button');
      botaoComentarios.className = 'btn btn-secondary';
      botaoComentarios.textContent = 'Comentários';
      botaoComentarios.onclick = () => abrirComentariosLivro(id);
      modalFooter.appendChild(botaoComentarios);
    })
    .catch(err => {
      console.error('Erro ao renderizar botão:', err);
    });
}


function abrirVisualizacaoLivro(botao) {
  const id = botao.getAttribute('data-id');
  const titulo = botao.getAttribute('data-titulo');
  document.getElementById('modalLivroLabel').textContent = titulo;

  // Fechar modais abertas que chamam esta modal
  const modalFavoritos = bootstrap.Modal.getInstance(document.getElementById("modalFavoritos"));
  if (modalFavoritos) modalFavoritos.hide();
  const modalIndicacoes = bootstrap.Modal.getInstance(document.getElementById("modalIndicacoes"));
  if (modalIndicacoes) modalIndicacoes.hide();
  const modalPendencias = bootstrap.Modal.getInstance(document.getElementById("modalPendenciasReservas"));
  if (modalPendencias) modalPendencias.hide();

  fetch('index.php?view=exibeLivro&id=' + id)
    .then(response => response.text())
    .then(data => {
      document.getElementById('modalLivroBody').innerHTML = data;

      const modalFooter = document.getElementById('modalLivroFooter');
      modalFooter.innerHTML = '';
      renderBotoesConformeReserva(id);

      const botaoFavoritar = document.getElementById('botao-favoritar');
      if (botaoFavoritar) {
        botaoFavoritar.setAttribute('data-id', id);

        fetch(`index.php?action=verificaFavorito&id=${id}`)
          .then(res => res.json())
          .then(json => {
            botaoFavoritar.setAttribute('data-favorito', json.favorito ? '1' : '0');
            configurarBotaoFavoritar();
          })
          .catch(() => {
            botaoFavoritar.setAttribute('data-favorito', '0');
            configurarBotaoFavoritar();
          });
      } else {
        configurarBotaoFavoritar();
      }

      fetch(`index.php?action=verificarDisponibilidadeLivro&id=${id}`)
        .then(res => res.json())
        .then(json => {
          const status = json.statusDisponibilidade;

          let textoStatus = "";
          let classeBadge = "badge bg-secondary text-light"; // padrão

          switch (status) {
            case 0:
              textoStatus = "Disponível";
              classeBadge = "badge bg-success text-light";
              break;
            case 1:
              textoStatus = "Indisponível";
              classeBadge = "badge bg-danger text-light";
              break;
            default:
              textoStatus = "";
          }

          const statusReservaEl = document.getElementById('badge-status-reserva');
          if (statusReservaEl && textoStatus) {
            statusReservaEl.textContent = textoStatus;
            statusReservaEl.className = classeBadge;
          }

        });

      const modalElement = document.getElementById("modalLivro");
      const modal = new bootstrap.Modal(modalElement);
      modal.show();

      const btnExpandirImagem = document.getElementById('btnExpandirImagem');
      const imagemCapa = document.getElementById('imagemCapaLivro');
      const imagemExpandida = document.getElementById('imagemExpandida');

      if (btnExpandirImagem && imagemCapa && imagemExpandida) {
        btnExpandirImagem.addEventListener('click', () => {
          imagemExpandida.src = imagemCapa.src;
          const modalImagem = new bootstrap.Modal(document.getElementById('modalImagemExpandida'));
          modalImagem.show();
        });
      }

    })
    .catch(error => {
      console.error('Erro ao carregar o livro:', error);
      document.getElementById('modalLivroBody').innerHTML = '<p class="text-danger">Erro ao carregar conteúdo.</p>';
    });
}

function solicitarReservaLivro(id) {
  fetch(`index.php?action=solicitarReservaLivro&id=${id}`)
    .then(response => response.json())
    .then(result => {
      if (result.status === 'sucesso') {
        exibirToast(result.mensagem);
      } else {
        exibirToast('Erro ao solicitar reserva.', 'erro');
      }
    })
    .catch(error => {
      exibirToast('Erro inesperado.', 'erro');
    });
}

function solicitarDevolucaoLivro(id) {
  fetch(`index.php?action=solicitarDevolucaoLivro&id=${id}`)
    .then(response => response.json())
    .then(result => {
      if (result.status === 'sucesso') {
        exibirToast(result.mensagem);
      } else {
        exibirToast('Erro ao solicitar devolução.', 'erro');
      }
    })
    .catch(error => {
      exibirToast('Erro inesperado.', 'erro');
    });
}



function abrirComentariosLivro(idLivro) {
  const inputIdLivro = document.getElementById('idLivroComentario');
  if (inputIdLivro) {
    inputIdLivro.value = idLivro;
  }

  fetch(`index.php?action=buscarComentarios&idLivro=${idLivro}`)
    .then(res => res.json())
    .then(data => {
      const comentarios = data.comentarios;
      const usuarioLogado = data.usuario;

      const lista = document.getElementById('listaComentarios');
      lista.innerHTML = '';

      if (comentarios.length === 0) {
        lista.innerHTML = `<p class="text-muted text-center">Nenhum comentário encontrado.</p>`;
        return;
      }

      comentarios.forEach(comentario => {
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.setAttribute('data-id', comentario.id);

        const conteudo = document.createElement('span');
        conteudo.innerHTML = `<strong>${comentario.usuario}:</strong> ${comentario.comentario}`;
        item.appendChild(conteudo);

        if (comentario.id_usuario === usuarioLogado) {
          const iconTrash = document.createElement('i');
          iconTrash.className = 'bi bi-trash text-danger';
          iconTrash.style.cursor = 'pointer';
          iconTrash.title = 'Excluir comentário';

          iconTrash.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            fetch(`index.php?action=excluirComentario&id=${comentario.id}`)
              .then(res => res.json())
              .then(data => {
                console.log('Resposta do servidor:', data);
                if (data.success === true) {
                  abrirComentariosLivro(idLivro);
                } else {
                  alert('Erro ao excluir comentário.');
                }
              })
              .catch(() => {
                alert('Erro ao excluir comentário.');
              });
          });

          iconTrash.addEventListener('mouseover', () => {
            iconTrash.classList.replace('bi-trash', 'bi-trash-fill');
          });

          iconTrash.addEventListener('mouseout', () => {
            iconTrash.classList.replace('bi-trash-fill', 'bi-trash');
          });

          item.appendChild(iconTrash);
        }

        lista.appendChild(item);
      });
    })
    .catch(err => {
      console.error('Erro ao carregar comentários:', err);
      const lista = document.getElementById('listaComentarios');
      lista.innerHTML = `<p class="text-danger text-center">Erro ao carregar comentários.</p>`;
    });
  configurarEnvioComentario();

  const modalComentarios = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalComentarios"));
  modalComentarios.show();
}

function configurarEnvioComentario() {
  const form = document.getElementById('formComentario');
  if (!form) return;

  // Clonar o formulário e substituir, eliminando event listeners antigos
  const novoForm = form.cloneNode(true);
  form.parentNode.replaceChild(novoForm, form);

  novoForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const comentario = document.getElementById('comentario').value.trim();
    const idLivro = document.getElementById('idLivroComentario').value;

    fetch('index.php?action=enviarComentario', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `comentario=${encodeURIComponent(comentario)}&idLivro=${idLivro}`
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.getElementById('comentario').value = '';
          abrirComentariosLivro(idLivro);
        } else {
          alert(data.mensagem || 'Erro ao enviar comentário.');
        }
      })
      .catch(err => {
        console.error('Erro ao enviar comentário:', err);
      });
  });
}



function abrirHistorico() {
  const modalHistoricoReservas = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalHistoricoReservas"));
  const lista = document.getElementById("listaHistorico");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarHistorico')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (!data || data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhum histórico encontrado.</p>';
        return;
      }

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Livro</th>
              <th>Ação</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.livro_titulo}</td>
                <td>${item.acao}</td>
                <td>${item.data || '-'}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      lista.appendChild(tableWrapper);
    })
    .catch(error => {
      console.error('Erro ao carregar histórico:', error);
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar histórico.</p>';
    });

  modalHistoricoReservas.show();
}

function abrirPendenciasReservas() {
  const modalPendencias = document.getElementById("modalPendenciasReservas");
  const lista = document.getElementById("listaPendencias");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarPendenciasPorUsuario')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhuma pendência encontrada.</p>';
        return;
      }

      data.forEach(item => {
        const a = document.createElement('a');
        a.href = '#';
        a.className = 'list-group-item list-group-item-action d-flex align-items-center justify-content-between';

        a.setAttribute('data-id', item.id_livro); // Corrigido aqui
        a.setAttribute('data-titulo', item.titulo);
        a.setAttribute('onclick', 'abrirVisualizacaoLivro(this)');

        const titulo = document.createElement('span');
        titulo.textContent = item.titulo;

        const DateTime = luxon.DateTime;
        const dataDevolucao = DateTime.fromISO(item.data_devolucao, { zone: 'America/Sao_Paulo' });
        const dataFormatada = dataDevolucao.toFormat('dd/LL/yyyy');

        const badge = document.createElement('span');
        badge.className = 'badge bg-danger';
        badge.textContent = `Em atraso desde ${dataFormatada}`;

        a.appendChild(titulo);
        a.appendChild(badge);

        lista.appendChild(a);
      });
    })
    .catch(error => {
      console.error('Erro ao buscar pendências:', error);
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar pendências.</p>';
    });

  new bootstrap.Modal(modalPendencias).show();
}


function abrirFavoritos() {
  const modalFavoritos = document.getElementById("modalFavoritos");
  const lista = document.getElementById("listaFavoritos");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarFavoritos')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhum favorito encontrado.</p>';
        return;
      }

      data.forEach(item => {
        const a = document.createElement('a');
        a.href = '#';
        a.className = 'list-group-item list-group-item-action d-flex align-items-center';

        a.setAttribute('data-id', item.id);
        a.setAttribute('data-titulo', item.titulo);
        a.setAttribute('onclick', 'abrirVisualizacaoLivro(this)');

        const titulo = document.createElement('span');
        titulo.textContent = item.titulo;

        const statusTag = document.createElement('span');
        statusTag.className = (item.disponivel ? 'badge bg-success' : 'badge bg-danger') + ' ms-auto';
        statusTag.textContent = item.disponivel ? 'Disponível' : 'Indisponível';

        a.appendChild(titulo);
        a.appendChild(statusTag);

        lista.appendChild(a);
      });
    })
    .catch(error => {
      console.error('Erro ao buscar favoritos:', error);
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar favoritos.</p>';
    });

  new bootstrap.Modal(modalFavoritos).show();
}

function abrirIndicacoes() {
  const modalIndicacoes = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalIndicacoes"));
  const lista = document.getElementById("listaIndicacoes");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('?action=buscarIndicacoes')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (!data || data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhuma indicação encontrada.</p>';
      } else {
        data.forEach(item => {

          const a = document.createElement('div');
          a.className = 'list-group-item list-group-item-action d-flex align-items-center justify-content-between';

          const leftContainer = document.createElement('div');
          leftContainer.className = 'd-flex align-items-center flex-grow-1';

          const iconTrash = document.createElement('i');
          iconTrash.className = 'bi bi-trash text-danger me-2';
          iconTrash.style.cursor = 'pointer';

          iconTrash.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            fetch(`index.php?action=excluirIndicacao&id=${item.id_indicacao}`)
              .then(res => res.text())
              .then(() => {
                abrirIndicacoes();
              })
              .catch(() => {
              });
          });

          iconTrash.addEventListener('mouseover', () => {
            iconTrash.classList.replace('bi-trash', 'bi-trash-fill');
          });

          iconTrash.addEventListener('mouseout', () => {
            iconTrash.classList.replace('bi-trash-fill', 'bi-trash');
          });

          const titulo = document.createElement('span');
          titulo.textContent = item.titulo;

          leftContainer.appendChild(iconTrash);
          leftContainer.appendChild(titulo);

          a.appendChild(leftContainer);

          if (item.existente) {
            const statusTag = document.createElement('span');
            statusTag.className = 'badge bg-success';
            statusTag.textContent = 'Adicionado';
            a.appendChild(statusTag);

            a.setAttribute('data-id', item.id);
            a.setAttribute('data-titulo', item.titulo);
            a.style.cursor = 'pointer';

            a.addEventListener('click', function () {
              abrirVisualizacaoLivro(this);
            });
          }

          lista.appendChild(a);
        });
      }

      modalIndicacoes.show();

      const modalIndicar = bootstrap.Modal.getInstance(document.getElementById("indicarLivro"));
      if (modalIndicar) {
        document.activeElement.blur();
        modalIndicar.hide();
      }
    })
    .catch(() => {
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar indicações.</p>';
    });
}


function abrirIndicarLivro() {
  const modalIndicar = bootstrap.Modal.getOrCreateInstance(document.getElementById("indicarLivro"));
  modalIndicar.show();

  const modalIndicacoes = bootstrap.Modal.getInstance(document.getElementById("modalIndicacoes"));
  if (modalIndicacoes) {
    document.activeElement.blur(); // tira o foco
    modalIndicacoes.hide();
  }
}

function abrirSolicitacoesPorUsuario() {
  const modalSolicitacoesPorUsuario = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalSolicitacoesPorUsuario"));
  const lista = document.getElementById("listaSolicitacoesPorUsuario");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarSolicitacoesPorUsuario')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhuma solicitação encontrada.</p>';
        return;
      }

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Livro</th>
              <th>Solicitação</th>
              <th> </th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.livro_titulo}</td>
                <td>${item.solicitacao || '-'}</td>
                <td class="btn-acoes">
                  <button class="btn btn-danger btn-sm" onclick="cancelarSolicitacaoPorUsuario(${item.id})">Cancelar</button>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      lista.appendChild(tableWrapper);
    })
    .catch(error => {
      console.error('Erro ao buscar solicitações:', error);
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar solicitações.</p>';
    });

  modalSolicitacoesPorUsuario.show();
}

function encerrarSessao() {
  new bootstrap.Modal("#modalEncerrarSessao").show();
}

function modalNotificacoes() {
  const lista = document.getElementById("listaNotificacoes");

  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarNotificacoes')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      if (data.length === 0) {
        lista.innerHTML = '<p class="text-muted text-center">Nenhuma notificação encontrada.</p>';
        return;
      }

      data.forEach(item => {
        const a = document.createElement('a');
        a.className = 'list-group-item list-group-item-action d-flex align-items-center';
        a.setAttribute('data-id', item.id);
        a.setAttribute('data-texto', item.texto);

        const texto = document.createElement('span');
        texto.textContent = item.texto;

        const iconTrash = document.createElement('i');
          iconTrash.className = 'bi bi-trash text-danger me-2';
          iconTrash.style.cursor = 'pointer';

          iconTrash.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            fetch(`index.php?action=excluirNotificacao&id=${item.id}`)
              .then(res => res.text())
              .then(() => {
                modalNotificacoes(); 
                verificarNotificacoes();
              })
              .catch(() => {
              });
          });

          iconTrash.addEventListener('mouseover', () => {
            iconTrash.classList.replace('bi-trash', 'bi-trash-fill');
          });

          iconTrash.addEventListener('mouseout', () => {
            iconTrash.classList.replace('bi-trash-fill', 'bi-trash');
          });

        a.appendChild(iconTrash);
        a.appendChild(texto);
        lista.appendChild(a);
      });
    })
    .catch(error => {
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar notificações.</p>';
    });
}

function abrirNotificacoes() {
  modalNotificacoes();

  const instanciaModal = new bootstrap.Modal(document.getElementById("modalNotificacoes"));
  instanciaModal.show();
}

function verificarNotificacoes() {
  const badge = document.getElementById("badgeNotificacoes");

  fetch('index.php?action=buscarNotificacoes')
    .then(response => response.json())
    .then(data => {
      if (data.length === 0) {
        badge.classList.add("d-none");
      } else {
        badge.classList.remove("d-none");
      }
    })
    .catch(error => {
      console.error('Erro ao verificar notificações:', error);
      badge.classList.add("d-none");
    });
}


/* exemplo abertura modal 
function abrirSolicitacoes() {
  const modalSolicitacoes = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalSolicitacoes"));
  modalSolicitacoes.show(); 
} */