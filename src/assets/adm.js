function configurarGuiasAdm(idContainer) {
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
      const res = await fetch(`index.php?action=configurarGuiasLivrosAdm&guia=${guia}&pagina=${pagina}&categoria=${encodeURIComponent(categoriaSelecionada)}&pesquisa=${encodeURIComponent(pesquisaAtual)}`);
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
            <a onclick="abrirVisualizacaoLivroAdm(this)" data-id="${livro.id}" data-titulo="${livro.titulo}">
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
    item.addEventListener('click', function (e) {
      e.preventDefault();
      categoriaSelecionada = this.getAttribute('data-id');
      carregarLivros(guiaAtual, 1);
    });
  });

  botaoPesquisa.addEventListener('click', function () {
    pesquisaAtual = inputPesquisa.value.trim();
    carregarLivros(guiaAtual, 1);
  });

  inputPesquisa.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      pesquisaAtual = inputPesquisa.value.trim();
      carregarLivros(guiaAtual, 1);
    }
  });

  carregarLivros(guiaAtual, 1);

  window.carregarLivros = carregarLivros;
}


function abrirSolicitacoes() {
  const modalSolicitacoes = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalSolicitacoes"));
  const lista = document.getElementById("listaSolicitacoes");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarSolicitacoes')
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
              <th>Usuário</th>
              <th>Solicitação</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.livro_titulo}</td>
                <td>${item.usuario_nome}</td>
                <td>${item.solicitacao || '-'}</td>
                <td class="btn-acoes">
                  <button class="btn btn-success btn-sm me-2" onclick="confirmarSolicitacao(${item.id})">Confirmar</button>
                  <button class="btn btn-danger btn-sm" onclick="cancelarSolicitacao(${item.id})">Cancelar</button>
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

  modalSolicitacoes.show();
}

function confirmarSolicitacao(id) {
  fetch(`index.php?action=confirmarSolicitacao&id=${id}`)
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        abrirSolicitacoes();
        exibirToast(result.mensagem);
      } else {
        exibirToast('Erro ao confirmar solicitação.', 'erro');
      }
    })
    .catch(error => {
      console.error('Erro ao confirmar:', error);
      exibirToast('Erro inesperado.', 'erro');
    });
}

function cancelarSolicitacao(id) {
  fetch(`index.php?action=cancelarSolicitacao&id=${id}`, {
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        abrirSolicitacoes();
        exibirToast(result.mensagem); // Mostra toast com a mensagem
      } else {
        exibirToast('Erro ao cancelar solicitação.', 'erro');
      }
    })
    .catch(error => {
      console.error('Erro ao cancelar:', error);
      exibirToast('Erro inesperado.', 'erro');
    });
}

function cancelarSolicitacaoPorUsuario(id) {
  fetch(`index.php?action=cancelarSolicitacao&id=${id}`, {
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        abrirSolicitacoesPorUsuario();
        exibirToast(result.mensagem);
      } else {
        exibirToast('Erro ao cancelar solicitação.', 'erro');
      }
    })
    .catch(error => {
      console.error('Erro ao cancelar:', error);
      exibirToast('Erro inesperado.', 'erro');
    });
}


function abrirCadastroCategorias() {
  const modalCadastroCategoria = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalCadastroCategoria"));
  const form = document.getElementById("formCadastroCategoria");

  const novoForm = form.cloneNode(true);
  form.replaceWith(novoForm);

  const input = novoForm.querySelector('input[name="novaCategoria"]');
  input.value = '';

  novoForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const novaCategoria = input.value.trim();

    const url = novoForm.action + '&novaCategoria=' + encodeURIComponent(novaCategoria);

    fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          exibirToast(data.mensagem, 'sucesso');
          input.value = '';
        } else {
          exibirToast(data.mensagem || 'Erro ao cadastrar categoria.', 'erro');
        }
      })
      .catch(() => {
        exibirToast('Erro inesperado.', 'erro');
      });
  });

  modalCadastroCategoria.show();
}

function abrirCadastroLivros() {
  const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalCadastroLivro"));
  const form = document.getElementById('formCadastroLivro');
  const selectCategoria = form.querySelector('select[name="categoria"]');

  selectCategoria.innerHTML = '<option selected disabled>Carregando categorias...</option>';

  fetch('index.php?action=buscarCategorias')
    .then(res => res.json())
    .then(categorias => {
      selectCategoria.innerHTML = '<option value="" disabled selected>Selecione uma categoria</option>';
      categorias.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.id;
        option.textContent = cat.categoria;
        selectCategoria.appendChild(option);
      });

      modal.show();
    })
    .catch(() => {
      selectCategoria.innerHTML = '<option disabled selected>Erro ao carregar categorias</option>';
      modal.show();
    });

  if (!form._listenerAdded) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: formData,
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            exibirToast(data.mensagem, 'sucesso');
            form.reset();          // limpa campos para novo cadastro
            // modal.hide();       // não fechar modal para manter na tela
          } else {
            exibirToast(data.mensagem || 'Livro já existente ou erro ao cadastrar.', 'erro');
          }
        })
        .catch(() => {
          exibirToast('Erro inesperado.', 'erro');
        });
    });
    form._listenerAdded = true;
  }
}

function abrirVisualizacaoLivroAdm(botao) {
  const id = botao.getAttribute('data-id');
  const titulo = botao.getAttribute('data-titulo');
  document.getElementById('modalLivroLabelAdm').textContent = titulo;

  fetch('index.php?view=exibeLivroAdm&id=' + id)
    .then(response => response.text())
    .then(data => {
      document.getElementById('modalLivroBodyAdm').innerHTML = data;

      const modalFooter = document.getElementById('modalLivroFooterAdm');
      modalFooter.innerHTML = '';

      const btnComentarios = document.createElement('button');
      btnComentarios.type = 'button';
      btnComentarios.className = 'btn btn-secondary';
      btnComentarios.textContent = 'Comentários';
      btnComentarios.onclick = () => abrirComentariosLivroAdm(id);
      modalFooter.appendChild(btnComentarios);

      let status = null;

      fetch(`index.php?action=verificarDisponibilidadeLivro&id=${id}`)
        .then(res => res.json())
        .then(json => {
          status = json.statusDisponibilidade;

          let textoStatus = "";
          let classeBadge = "badge bg-secondary text-light";

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

          const iconeInfo = document.getElementById('btnInformacoes');
          if (iconeInfo) {
            if (status === 1) {
              iconeInfo.classList.remove('d-none');
              iconeInfo.addEventListener('mouseenter', () => {
                iconeInfo.classList.remove('bi-info-circle');
                iconeInfo.classList.add('bi-info-circle-fill');
              });
              iconeInfo.addEventListener('mouseleave', () => {
                iconeInfo.classList.remove('bi-info-circle-fill');
                iconeInfo.classList.add('bi-info-circle');
              });
              iconeInfo.onclick = () => exibeInformacoesLivroRetirado(id);
            } else {
              iconeInfo.classList.add('d-none');
            }
          }
        });

      const iconeModificar = document.getElementById('btnModificarLivro');
      if (iconeModificar) {
        iconeModificar.addEventListener('mouseenter', () => {
          iconeModificar.classList.remove('bi-pencil');
          iconeModificar.classList.add('bi-pencil-fill');
        });

        iconeModificar.addEventListener('mouseleave', () => {
          iconeModificar.classList.remove('bi-pencil-fill');
          iconeModificar.classList.add('bi-pencil');
        });

        iconeModificar.onclick = () => abrirModificarLivro(id);
      }

      const iconeExcluir = document.getElementById('btnExcluirLivro');
      if (iconeExcluir) {
        iconeExcluir.addEventListener('mouseenter', () => {
          iconeExcluir.classList.remove('bi-trash');
          iconeExcluir.classList.add('bi-trash-fill');
        });

        iconeExcluir.addEventListener('mouseleave', () => {
          iconeExcluir.classList.remove('bi-trash-fill');
          iconeExcluir.classList.add('bi-trash');
        });

        iconeExcluir.onclick = () => excluirLivro(id);
      }

      const modalElement = document.getElementById("modalLivroAdm");
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
      document.getElementById('modalLivroBodyAdm').innerHTML = '<p class="text-danger">Erro ao carregar conteúdo.</p>';
    });
}

function exibeInformacoesLivroRetirado(id) {
  const modalInformacoes = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalInformacoesAdm"));

  document.getElementById("infoUsuario").textContent = 'Carregando...';
  document.getElementById("infoRetirada").textContent = 'Carregando...';
  document.getElementById("infoDevolucao").textContent = 'Carregando...';

  fetch(`index.php?action=buscarInformacoes&id=${id}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById("infoUsuario").textContent = data.usuario;
      document.getElementById("infoRetirada").textContent = data.data_retirada;
      document.getElementById("infoDevolucao").textContent = data.data_devolucao;
      modalInformacoes.show();
    })
    .catch(error => {
      console.error('Erro ao buscar informações:', error);
    });
}

function abrirComentariosLivroAdm(idLivro) {
  const inputIdLivro = document.getElementById('idLivroComentario');
  if (inputIdLivro) {
    inputIdLivro.value = idLivro;
  }

  fetch(`index.php?action=buscarComentarios&idLivro=${idLivro}`)
    .then(res => res.json())
    .then(data => {
      const comentarios = data.comentarios;

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
                abrirComentariosLivroAdm(idLivro);
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

        lista.appendChild(item);
      });
    })
    .catch(err => {
      console.error('Erro ao carregar comentários:', err);
      const lista = document.getElementById('listaComentarios');
      lista.innerHTML = `<p class="text-danger text-center">Erro ao carregar comentários.</p>`;
    });

  const modalComentariosAdm = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalComentariosAdm"));
  modalComentariosAdm.show();
}

function excluirLivro(id) {
  const modalExcluirEl = document.getElementById("modalExcluirLivro");
  const modalExcluir = bootstrap.Modal.getOrCreateInstance(modalExcluirEl);
  const btnConfirmar = document.getElementById("btnConfirmarExclusao");

  btnConfirmar.replaceWith(btnConfirmar.cloneNode(true));
  const novoBotao = document.getElementById("btnConfirmarExclusao");

  novoBotao.addEventListener('click', () => {
    fetch(`index.php?action=excluirLivro&id=${id}`)
      .then(response => response.json())
      .then(result => {
        modalExcluir.hide();
        if (result.success) {
          const modalLivroAdm = bootstrap.Modal.getInstance(document.getElementById("modalLivroAdm"));
          if (modalLivroAdm) modalLivroAdm.hide();
          exibirToast(result.mensagem);
          setTimeout(() => {
            window.location.href = 'http://localhost/index.php?action=telaInicialAdm';
          }, 1500);
        } else {
          exibirToast('Erro ao excluir livro.');
        }
      })
      .catch(() => {
        modalExcluir.hide();
        exibirToast('Erro inesperado.');
      });
  });

  modalExcluir.show();
}

function abrirModificarLivro(id) {
  fetch(`index.php?action=buscarDadosLivro&id=${id}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro na requisição: ' + response.status);
      }
      return response.json();
    })
    .then(livro => {
      const modalElement = document.getElementById("modalModificacaoLivro");
      const modal = new bootstrap.Modal(modalElement);

      const form = document.getElementById("formModificarLivro");
      form.reset();

      form.querySelector('input[name="titulo"]').value = livro.titulo || '';
      form.querySelector('input[name="autor"]').value = livro.autor || '';
      form.querySelector('input[name="data_lancamento"]').value = livro.data_lancamento || '';
      form.querySelector('select[name="categoria"]').value = livro.id_categoria || '';
      form.querySelector('textarea[name="sinopse"]').value = livro.sinopse || '';

      const imgCapaAtual = document.getElementById('imgCapaAtual');
      if (livro.caminho_capa) {
        imgCapaAtual.src = '/' + livro.caminho_capa;
        imgCapaAtual.style.display = 'block';
      } else {
        imgCapaAtual.style.display = 'none';
        imgCapaAtual.src = '';
      }

      let inputId = form.querySelector('input[name="id"]');
      if (!inputId) {
        inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        form.appendChild(inputId);
      }
      inputId.value = id;

      modal.show();
    })
    .catch(err => {
      console.error("Erro ao buscar livro:", err);
      alert("Erro ao carregar dados do livro.");
    });
}

function modificarLivro() {
  const form = document.getElementById('formModificarLivro');
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      const modalElement = document.getElementById("modalModificacaoLivro");
      const modal = bootstrap.Modal.getInstance(modalElement);
      modal.hide();
      const modalLivroAdm = bootstrap.Modal.getInstance(document.getElementById("modalLivroAdm"));
      if (modalLivroAdm) modalLivroAdm.hide();
      exibirToast(data.mensagem);
      setTimeout(() => {
        window.location.href = 'http://localhost/index.php?action=telaInicialAdm';
      }, 1500);
    })
    .catch(error => {
      exibirToast("Erro ao modificar o livro.");
    });
}

document.getElementById('formModificarLivro').addEventListener('submit', function (event) {
  event.preventDefault();
  modificarLivro();
});



function abrirHistoricoPorUsuario() {
  const modalHistoricoUsuario = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalHistoricoPorUsuario"));
  modalHistoricoUsuario.show();
  document.getElementById('inputUsuario').value = '';
  document.getElementById('listaHistorico').innerHTML = '';
}

function buscarHistoricoPorUsuario() {
  const usuario = document.getElementById('inputUsuario').value.trim();
  const resultado = document.getElementById('listaHistorico');

  resultado.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch(`index.php?action=buscarHistoricoPorUsuario&usuario=${encodeURIComponent(usuario)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Erro na requisição');
      return response.json();
    })
    .then(dados => {
      resultado.innerHTML = '';

      if (!dados || dados.length === 0) {
        resultado.innerHTML = '<p class="text-muted text-center">Nenhum histórico encontrado para este usuário.</p>';
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
          ${dados.map(item => `
            <tr>
              <td>${item.titulo || item.livro_titulo || '-'}</td>
              <td>${item.acao || '-'}</td>
              <td>${item.data || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;

      resultado.appendChild(tableWrapper);
    })
    .catch(error => {
      resultado.innerHTML = '<p class="text-danger text-center">Erro ao carregar histórico.</p>';
    });
}

function abrirHistoricoPorLivro() {
  const modalHistoricoLivro = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalHistoricoPorLivro"));
  modalHistoricoLivro.show();
  document.getElementById('inputLivro').value = '';
  document.getElementById('listaHistoricoPorLivro').innerHTML = '';
}

function buscarHistoricoPorLivro() {
  const livro = document.getElementById('inputLivro').value.trim();
  const resultado = document.getElementById('listaHistoricoPorLivro');

  resultado.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch(`index.php?action=buscarHistoricoPorLivro&livro=${encodeURIComponent(livro)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Erro na requisição');
      return response.json();
    })
    .then(dados => {
      console.log('Dados recebidos:', dados);
      resultado.innerHTML = '<p>Teste de exibição do histórico funcionando!</p>';
      resultado.innerHTML = '';

      if (!dados || dados.length === 0) {
        resultado.innerHTML = '<p class="text-muted text-center">Nenhum histórico encontrado para este livro.</p>';
        return;
      }

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          ${dados.map(item => `
            <tr>
              <td>${item.usuario || '-'}</td>
              <td>${item.acao || '-'}</td>
              <td>${item.data || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;

      resultado.appendChild(tableWrapper);
    })
    .catch(error => {
      resultado.innerHTML = '<p class="text-danger text-center">Erro ao carregar histórico.</p>';
    });
}

function abrirPendenciasPorUsuarioAdm() {
  const modalPendenciasPorUsuario = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalPendenciasPorUsuario"));
  modalPendenciasPorUsuario.show();
  document.getElementById('inputUsuarioPendenciasAdm').value = '';
  document.getElementById('listaPendenciasPorUsuarioAdm').innerHTML = '';
}

function buscarPendenciasPorUsuarioAdm() {
  const usuario = document.getElementById('inputUsuarioPendenciasAdm').value.trim();
  const resultado = document.getElementById('listaPendenciasPorUsuarioAdm');

  resultado.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch(`index.php?action=buscarPendenciasPorUsuarioAdm&usuario=${encodeURIComponent(usuario)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Erro na requisição');
      return response.json();
    })
    .then(dados => {
      resultado.innerHTML = '';

      if (!dados || dados.length === 0) {
        resultado.innerHTML = '<p class="text-muted text-center">Nenhuma pendência encontrada para este usuário.</p>';
        return;
      }

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>Livro</th>
            <th>Data devolução</th>
            <th>Dias em atraso</th>
          </tr>
        </thead>
        <tbody>
          ${dados.map(item => `
            <tr>
              <td>${item.titulo || '-'}</td>
              <td>${item.data_devolucao || '-'}</td>
              <td>${item.dias_atraso || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;

      resultado.appendChild(tableWrapper);
    })
    .catch(error => {
      resultado.innerHTML = '<p class="text-danger text-center">Erro ao carregar pendências.</p>';
    });
}

function abrirPendenciasPorLivroAdm() {
  const modalPendenciasPorLivro = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalPendenciasPorLivro"));
  modalPendenciasPorLivro.show();
  document.getElementById('inputLivroPendenciasAdm').value = '';
  document.getElementById('listaPendenciasPorLivroAdm').innerHTML = '';
}

function buscarPendenciasPorLivroAdm() {
  const livro = document.getElementById('inputLivroPendenciasAdm').value.trim();
  const resultado = document.getElementById('listaPendenciasPorLivroAdm');

  resultado.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch(`index.php?action=buscarPendenciasPorLivroAdm&livro=${encodeURIComponent(livro)}`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Erro na requisição');
      return response.json();
    })
    .then(dados => {
      resultado.innerHTML = '';

      if (!dados || dados.length === 0) {
        resultado.innerHTML = '<p class="text-muted text-center">Nenhuma pendência encontrada para este livro.</p>';
        return;
      }

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>Usuário</th>
            <th>Data devolução</th>
            <th>Dias em atraso</th>
          </tr>
        </thead>
        <tbody>
          ${dados.map(item => `
            <tr>
              <td>${item.usuario || '-'}</td>
              <td>${item.data_devolucao || '-'}</td>
              <td>${item.dias_atraso || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
    `;

      resultado.appendChild(tableWrapper);
    })
    .catch(error => {
      resultado.innerHTML = '<p class="text-danger text-center">Erro ao carregar pendências.</p>';
    });
}

function abrirIndicacoesAdm() {
  const modalIndicacoesAdm = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalSIndicacoesAdm"));
  const lista = document.getElementById("listaIndicacoesAdm");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarIndicacoesAdm')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Título</th>
			  <th>Autor</th>
              <th>Indicações</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.titulo}</td>
				        <td>${item.autor}</td>
                <td>${item.total_indicacoes}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      lista.appendChild(tableWrapper);
    })
    .catch(error => {
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar indicações.</p>';
    });

  modalIndicacoesAdm.show();
}

function abrirLivrosEmAtraso() {
  const modalLivrosEmAtraso = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalLivrosEmAtraso"));
  const lista = document.getElementById("listaLivrosEmAtraso");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarLivrosEmAtraso')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Título</th>
			        <th>Autor</th>
              <th>Dias em atraso</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.titulo}</td>
				        <td>${item.autor}</td>
                <td>${item.dias_atraso}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      lista.appendChild(tableWrapper);
    })
    .catch(error => {
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar.</p>';
    });

  modalLivrosEmAtraso.show();
}

function abrirUsuariosEmAtraso() {
  const modalUsuariosEmAtraso = bootstrap.Modal.getOrCreateInstance(document.getElementById("modalUsuariosEmAtraso"));
  const lista = document.getElementById("listaUsuariosEmAtraso");
  lista.innerHTML = '<p class="text-muted text-center">Carregando...</p>';

  fetch('index.php?action=buscarUsuariosEmAtraso')
    .then(response => response.json())
    .then(data => {
      lista.innerHTML = '';

      const tableWrapper = document.createElement('div');
      tableWrapper.classList.add('table-responsive');

      tableWrapper.innerHTML = `
        <table class="table table-bordered align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Usuário</th>
              <th>Livros em atraso</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(item => `
              <tr>
                <td>${item.usuario}</td>
                <td>${item.total_pendencias}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;

      lista.appendChild(tableWrapper);
    })
    .catch(error => {
      lista.innerHTML = '<p class="text-danger text-center">Erro ao carregar.</p>';
    });

  modalUsuariosEmAtraso.show();
}