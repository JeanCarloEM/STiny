(function ($, w, _) {
  (function (wp, blocks, editor, components, i18n, el, Fragment, RichText, BlockControls, AlignmentToolbar, inspetor) {

    blocks.registerBlockType('gutenberg-aboutbar13/aboutbar1-3', {
      title: 'AboutBAR-1-3',
      icon: 'media-spreadsheet',
      category: 'layout',

      attributes: {
        block_name: {
          type: "string",
          source: 'attribute',
          attribute: 'data-block-name',
          selector: 'div'
        },
        principal_ico: {
          type: "string",
          source: 'attribute',
          attribute: 'class',
          selector: '.about-bar .about i'

        },
        principal_title: {
          type: "string",
          source: 'html',
          selector: '.about-bar .about h3'
        },
        principal_url: {
          type: "string",
          source: 'attribute',
          attribute: 'href',
          selector: '.about-bar .about a'
        },
        principal_more: {
          type: "string",
          source: 'html',
          selector: '.about-bar .about a'
        },
        principal_text: {
          type: "string",
          source: 'html',
          selector: '.about-bar .about div'
        },

        c1_ico: {
          type: "string",
          source: 'attribute',
          attribute: 'class',
          selector: '.about-bar > .columns3 > .c1 i'
        },
        c1_title: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c1 h4'
        },
        c1_url: {
          type: "string",
          source: 'attribute',
          attribute: 'href',
          selector: '.about-bar > .columns3 > .c1'
        },
        c1_text: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c1 div'
        },

        c2_ico: {
          type: "string",
          source: 'attribute',
          attribute: 'class',
          selector: '.about-bar > .columns3 > .c2 i'
        },
        c2_title: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c2 h4'
        },
        c2_url: {
          type: "string",
          source: 'attribute',
          attribute: 'href',
          selector: '.about-bar > .columns3 > .c2'
        },
        c2_text: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c2 div'
        },

        c3_ico: {
          type: "string",
          source: 'attribute',
          attribute: 'class',
          selector: '.about-bar > .columns3 > .c3 i'
        },
        c3_title: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c3 h4'
        },
        c3_url: {
          type: "string",
          source: 'attribute',
          attribute: 'href',
          selector: '.about-bar > .columns3 > .c3'
        },
        c3_text: {
          type: "string",
          source: 'html',
          selector: '.about-bar > .columns3 > .c3 div'
        }
      },

      edit: function (props) {
        function onChangeContent(newContent, e) {
          console.log(newContent);
          console.log(e);
          props.setAttributes({content: newContent});
        }

        function onChangeAlignment(newAlignment) {
          props.setAttributes({alignment: newAlignment});
        }

        return (
                el('div', {
                  className: 'jcemeditor about-bar'
                },
                        [
                          el(
                                  'div',
                                  {
                                    className: 'title'
                                  },
                                  ['AboutBar-1-3', w.addBlockEditorFieldText.bind(props)('Block Name', 'block_name')]
                                  ),
                          !props.isSelected ? '' : w.addBlockPannels([
                            ["Principal", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'As informações principais, exibidas na barra esquerda.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Icone Class', 'principal_ico'),
                                w.addBlockEditorField.bind(props)('Titulo', 'principal_title'),
                                w.addBlockEditorField.bind(props)('Texto', 'principal_text'),
                                w.addBlockEditorField.bind(props)('Texto do Link', 'principal_more'),
                                w.addBlockEditorFieldText.bind(props)('Link', 'principal_url')
                              ]],
                            ["Coluna 1", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'As informações exibidas na 1º coluna.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Icone Class', 'c1_ico'),
                                w.addBlockEditorField.bind(props)('Titulo', 'c1_title'),
                                w.addBlockEditorField.bind(props)('Texto', 'c1_text'),
                                w.addBlockEditorFieldText.bind(props)('Link', 'c1_url')
                              ]],
                            ["Coluna 2", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'As informações exibidas na 2º coluna.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Icone Class', 'c2_ico'),
                                w.addBlockEditorField.bind(props)('Titulo', 'c2_title'),
                                w.addBlockEditorField.bind(props)('Texto', 'c2_text'),
                                w.addBlockEditorFieldText.bind(props)('Link', 'c2_url')
                              ]],
                            ["Coluna 3", [
                                el(
                                        'div',
                                        {
                                          className: 'info'
                                        },
                                        'As informações exibidas na 3º coluna.'
                                        ),
                                w.addBlockEditorFieldText.bind(props)('Icone Class', 'c3_ico'),
                                w.addBlockEditorField.bind(props)('Titulo', 'c3_title'),
                                w.addBlockEditorField.bind(props)('Texto', 'c3_text'),
                                w.addBlockEditorFieldText.bind(props)('Link', 'c3_url')
                              ]]
                          ]
                                  )
                        ]
                        ));
      },

      save: function (props) {
        var x = el(RichText.Content, {
          tagName: 'div',
          'data-block-name': props.attributes.block_name,
          className: 'about-bar',
          value: [
            /* :: COLUNAS :: */
            el(RichText.Content, {
              tagName: 'div',
              className: 'columns3',
              value: [
                /* COLUNA 1 */
                el(RichText.Content, {
                  tagName: 'a',
                  target: '_blank',
                  rel: "noopener noreferrer",
                  href: props.attributes.c1_url,
                  className: 'c1',
                  value: [
                    el(RichText.Content, {
                      tagName: 'i',
                      className: props.attributes.c1_ico
                    }),
                    el(RichText.Content, {
                      tagName: 'h4',
                      value: props.attributes.c1_title
                    }),
                    el(RichText.Content, {
                      tagName: 'div',
                      value: props.attributes.c1_text
                    })
                  ]}),
                /* COLUNA 2 */
                el(RichText.Content, {
                  tagName: 'a',
                  target: '_blank',
                  rel: "noopener noreferrer",
                  href: props.attributes.c2_url,
                  className: 'c2',
                  value: [
                    el(RichText.Content, {
                      tagName: 'i',
                      className: props.attributes.c2_ico
                    }),
                    el(RichText.Content, {
                      tagName: 'h4',
                      value: props.attributes.c2_title
                    }),
                    el(RichText.Content, {
                      tagName: 'div',
                      value: props.attributes.c2_text
                    })
                  ]}),
                /* COLUNA 3 */
                el(RichText.Content, {
                  tagName: 'a',
                  target: '_blank',
                  rel: "noopener noreferrer",
                  href: props.attributes.c3_url,
                  className: 'c3',
                  value: [
                    el(RichText.Content, {
                      tagName: 'i',
                      className: props.attributes.c3_ico
                    }),
                    el(RichText.Content, {
                      tagName: 'h4',
                      value: props.attributes.c3_title
                    }),
                    el(RichText.Content, {
                      tagName: 'div',
                      value: props.attributes.c3_text
                    })
                  ]})
              ]}),

            el(RichText.Content, {
              tagName: 'div',
              className: 'about',
              value: [
                el(RichText.Content, {
                  tagName: 'i',
                  className: props.attributes.principal_ico
                }),
                el(RichText.Content, {
                  tagName: 'h3',
                  value: props.attributes.principal_title
                }),
                el(RichText.Content, {
                  tagName: 'div',
                  className: 'content',
                  value: props.attributes.principal_text
                }),
                el(RichText.Content, {
                  tagName: 'a',
                  target: '_blank',
                  rel: "noopener noreferrer",
                  href: props.attributes.principal_url,
                  className: 'more',
                  value: props.attributes.principal_more
                })
              ]})
          ]});

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