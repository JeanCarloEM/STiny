(function ($, w, _) {
  (function (wp, blocks, editor, cp, i18n, el, Fragment, RichText, BlockControls, AlignmentToolbar, inspetor) {
    w.addBlockPannels = function (cts) {
      cts = Array.isArray(cts) ? cts : [];

      var tabs = [];
      var html = {};
      for (var i = 0; i < cts.length; i++) {
        var nm = cts[i][0].replace(/[^\w]/, '').toLowerCase();
        tabs.push({
          name: nm,
          title: cts[i][0],
          className: nm
        });

        html[nm] = cts[i][1];
      }

      return el('div', {className: 'tabs'}, el(cp.TabPanel, {'tabs': tabs}, function (tab) {
        return html[tab.name];
      }))
    };

    w.addBlockEditorFieldBASE = function (tipo, el_attrs, placeholder, prop, tag, attr) {
      tag = ((typeof tag !== 'string') || (tag === '')) ? 'div' : tag;
      attr = (typeof attr === 'object') ? attr : {};

      var getVal = (typeof prop === 'function')
              ? prop.bind(this)
              : function (valor) {
                if ((typeof valor === 'string') || (typeof valor === 'number')) {
                  /* PRECISA DISSO, HA UM BUG COM ALGUNS COMPONENTES GUTEMBERG
                   * QUE NAO PERMITE FAZER EDIÇÃO DEPOIS DE SALVO E RECARREGADO
                   */
                  var r = {};
                  r[prop] = valor;
                  this.setAttributes(r);
                }


                return this.attributes[prop];
              }.bind(this);

      el_attrs = (typeof el_attrs === 'object') ? el_attrs : {};

      return el(
              tag,
              Object.assign({
                className: 'field',
                'data-place': placeholder
              }, attr),
              el(tipo, Object.assign({
                value: getVal(),
                onChange: getVal
              }, el_attrs))
              );
    };

    w.addBlockEditorField = function (placeholder, prop, tag, attr) {
      return w.addBlockEditorFieldBASE.bind(this)(RichText, {}, placeholder, prop, tag, attr);
    };

    w.addBlockEditorFieldTextArea = function (placeholder, prop, tag, attr) {
      return w.addBlockEditorFieldBASE.bind(this)(cp.TextareaControl, {}, placeholder, prop, tag, attr);
    };

    w.addBlockEditorFieldText = function (placeholder, prop, tag, attr) {
      return w.addBlockEditorFieldBASE.bind(this)(cp.TextControl, {type: ((typeof attr === 'object' && attr['type']) ? attr['type'] : 'text')}, placeholder, prop, tag, attr);
    };
    w.addBlockEditorColor = function (placeholder, prop, tag, attr) {
      return w.addBlockEditorFieldBASE.bind(this)(cp.ColorPalette, {}, placeholder, prop, tag, attr);
    };

    w.addBlockEditorDropBox = function (placeholder, prop, opts, tag, attr) {
      opts = Array.isArray(opts) ? opts : [];
      for (var i = 0; i < opts.length; i++) {
        opts[i] = (typeof opts[i] === 'object') ? opts[i] : {
          value: opts[i].replace(/[^\w]/, '').toLowerCase(),
          label: opts[i]
        };
      }

      return w.addBlockEditorFieldBASE.bind(this)(cp.SelectControl, {options: opts}, placeholder, prop, tag, attr);
    };

    /*
     *
     */
    w.addMultipleEditorField = function (attr_filters, creator) {
      var r = [];

      if ((typeof attr_filters === 'object') || (Array.isArray(attr_filters))) {
        for (var i = 0; i < attr_filters.length; i++) {
          r.push(((attr_filters[i].hasOwnProperty('create') && (typeof attr_filters[i].create === 'function')) ? attr_filters[i].create.bind(this) : ((typeof creator === 'function') ? creator.bind(this) : w.addBlockEditorFieldTextArea.bind(this)))(attr_filters[i], attr_filters[i].trim().toLowerCase().replace(/[^\w]/, '')));
        }
      }

      return r;
    };
    /*
     *
     */
    w.addMultipleEditorFieldTagModel = function (attr_filters) {
      var r = [];

      if ((typeof attr_filters === 'object') || (Array.isArray(attr_filters))) {
        for (var i = 0; i < attr_filters.length; i++) {
          r.push(el(RichText.Content, {
            tagName: attr_filters[i].trim().toLowerCase().replace(/[^\w]/, ''),
            value: this.attributes[attr_filters[i]]
          }));
        }
      }

      return r;
    };

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