(function ($, w, _) {
  (function (wp, blocks, editor, cp, i18n, el, Fragment, RichText, BlockControls, AlignmentToolbar, inspetor) {
    var atributos = {
      block_name: {
        type: "string",
        source: 'attribute',
        attribute: 'data-block-name',
        selector: 'div'
      },
      block_color: {
        type: "string",
        source: 'attribute',
        attribute: 'data-block-color',
        selector: 'div'
      },

      post_type: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > posttype'
      },

      categorias: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > categorias'
      },

      tags: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > tags'
      },

      onlyexcerpt: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > onlyexcerpt'
      },

      postqtd: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > postqtd'
      },

      more_options: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > more'
      },

      querymode: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > mode'
      },

      prehtml: {
        type: "string",
        source: 'html',
        selector: 'div jcemquery > prehtml'
      },

      poshtml: {
        type: "string",
        source: 'html',
        selector: 'div jcemquery > poshtml'
      },

      parser: {
        type: "string",
        source: 'html',
        selector: 'div jcemquery > parser'
      },

      function_pass: {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > functionpass'
      }
    };

    /*
     * FILTROS WP_QUERY
     * https://codex.wordpress.org/Plugin_API/Filter_Reference
     */
    var attr_filters = ['found_posts'
              , 'found_posts_query'
              , 'post_limits'
              , 'posts_clauses'
              , 'posts_request'
              , 'posts_distinct'
              , 'posts_fields'
              , 'posts_groupby'
              , 'posts_join'
              , 'posts_join_paged'
              , 'posts_orderby'
              , 'posts_request'
              , 'posts_results'
              , 'posts_search'
              , 'posts_where'
              , 'posts_where_paged'
              , 'the_posts'];

    for (var i = 0; i < attr_filters.length; i++) {
      atributos[attr_filters[i]] = {
        type: "string",
        source: 'text',
        selector: 'div jcemquery > filters > ' + attr_filters[i]
      };
    }

    blocks.registerBlockType('jcem/query', {
      title: 'Query',
      icon: 'media-spreadsheet',
      category: 'layout',

      /*
       * $post_type = null, $categorias = null, $tags = null, $onlyExcerpt = null, $postQtd = null, $more_options = null
       */
      attributes: atributos,

      edit: function (props) {
        return (
                el('div', {
                  className: 'jcemeditor jcemquery'
                },
                        [
                          el(
                                  'div',
                                  {
                                    className: 'title'
                                  },
                                  ['QUERY', w.addBlockEditorFieldText.bind(props)('Block Name', 'block_name')]
                                  ),

                          !props.isSelected ? '' : w.addBlockPannels([
                            ["Geral", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'Post Types, Categorias e Tags podem conter valores múltiplos separados por vigula.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Posts Types', 'post_type'),
                                w.addBlockEditorFieldText.bind(props)('Categorias', 'categorias'),
                                w.addBlockEditorFieldText.bind(props)('Tags', 'tags'),
                                w.addBlockEditorFieldText.bind(props)('onlyExcerpt', 'onlyexcerpt', null, {type: "number"}),
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'onlyExcertp é numérico e informa a partir de qual número de posts retornados conterão somente o excerto (resumo); por exemplo, se informado 3, os 3 primeiros posts conterão todo o conteúdo e os demais, apenas o resumo.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Quantidade', 'postqtd', null),
                                w.addBlockEditorFieldTextArea.bind(props)('Mais Opções (Json)', 'more_options')
                              ]],
                            ["Filtros", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'Aqui você pode adicionar os filtros para a query. Para usar a tabela, você precisa especificar  seu prefixo, que pode ser obtido usando #($prefix). Você pode expecificar o texto que deseja que seja acrescentado ou, informar um JSON, contendo o "PRE", "POS" ou "REPLACE" para inserir o texto antes, depois ou substituir, respectivamente. Para entender melhor os filtros, você pode obter informações em https://codex.wordpress.org/Plugin_API/Filter_Reference'
                                        )
                              ].concat(w.addMultipleEditorField.bind(props)(attr_filters))],
                            ["Mode", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'Não funciona para o modo "forshortcode". E o campo "Parser HTML" provavelmente funciona apenas no modo parser.'
                                        ),
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'Does not work for "forshortcode" mode. And the "Parser HTML" field already works only in parser mode.'
                                        ),
                                w.addBlockEditorDropBox.bind(props)('Query Mode', 'querymode', ['For Shortcode', 'JCEM-Slider', "Article", "Parser"]),
                                w.addBlockEditorFieldTextArea.bind(props)('Passagem de Opções', 'function_pass'),
                                w.addBlockEditorFieldTextArea.bind(props)('Pre HTML', 'prehtml'),
                                w.addBlockEditorFieldTextArea.bind(props)('Pós HTML', 'poshtml'),
                                w.addBlockEditorFieldTextArea.bind(props)('Parser HTML', 'parser')
                              ]]
                          ])
                        ]
                        ));

      },

      /*
       * https://stackoverflow.com/questions/16395115/wp-query-check-if-the-post-content-is-empty
       */

      save: function (props) {
        var x = el(RichText.Content, {
          tagName: 'div',
          'data-block-name': props.attributes.block_name,
          'data-block-color': props.attributes.block_color,
          value: ["[jcemquery]", el(RichText.Content, {
              tagName: 'jcemquery',
              value: [
                el(RichText.Content, {
                  tagName: 'posttype',
                  value: props.attributes.post_type
                }),
                el(RichText.Content, {
                  tagName: 'categorias',
                  value: props.attributes.categorias
                }),
                el(RichText.Content, {
                  tagName: 'tags',
                  value: props.attributes.tags
                }),
                el(RichText.Content, {
                  tagName: 'onlyexcerpt',
                  value: props.attributes.onlyexcerpt
                }),
                el(RichText.Content, {
                  tagName: 'postqtd',
                  value: props.attributes.postqtd
                }),
                el(RichText.Content, {
                  tagName: 'more',
                  value: props.attributes.more_options
                }),
                el(RichText.Content, {
                  tagName: 'mode',
                  value: props.attributes.querymode
                }),
                el(RichText.Content, {
                  tagName: 'prehtml',
                  value: props.attributes.prehtml
                }),
                el(RichText.Content, {
                  tagName: 'poshtml',
                  value: props.attributes.poshtml
                }),
                el(RichText.Content, {
                  tagName: 'parser',
                  value: props.attributes.parser
                }),
                el(RichText.Content, {
                  tagName: 'functionpass',
                  value: props.attributes.function_pass
                }),
                el(RichText.Content, {
                  tagName: 'filters',
                  value: w.addMultipleEditorFieldTagModel.bind(props)(attr_filters)
                })
              ]
            })
                    , "[/jcemquery]"]}
        );

        return x;
      }
    });

  })(
          w.wp,
          w.wp.blocks,
          w.wp.editor,
          w.wp.components,
          w.wp.i18n,
          w.wp.element.createElement,
          w.wp.element.Fragment,
          w.wp.editor.RichText,
          w.wp.editor.BlockControls,
          w.wp.editor.AlignmentToolbar,
          w.wp.editor.InspectorControls
          );
})(jQuery, window, document);