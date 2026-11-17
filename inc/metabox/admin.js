jQuery(function ($) {
  // RANGE: exibir valor ao lado
  $(document).on('input change', 'input[type="range"]', function () {
    const id = $(this).attr('id');
    $('.campos-range-value[data-for="' + id + '"]').text($(this).val());
  });

  // TEL mask simples (opcional): usa data-mask, ex: "(99) 99999-9999"
  function applyMask(value, mask) {
    let v = value.replace(/\D/g, '');
    let out = '';
    let vi = 0;
    for (let i = 0; i < mask.length; i++) {
      if (mask[i] === '9') {
        if (vi >= v.length) break;
        out += v[vi++];
      } else {
        out += mask[i];
      }
    }
    return out;
  }

  $(document).on('input', 'input[data-mask]', function () {
    const mask = $(this).data('mask');
    $(this).val(applyMask($(this).val(), mask));
  });

  // MEDIA uploader
  let frame;
  $(document).on('click', '.campos-media-select', function (e) {
    e.preventDefault();

    const target = $(this).data('target');
    const $wrap = $(this).closest('.campos-media-field');
    const mime = $wrap.data('mime') || '';

    frame = wp.media({
      title: 'Selecionar arquivo',
      button: { text: 'Usar este arquivo' },
      multiple: false,
      library: mime ? { type: mime } : {},
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      $('#' + target).val(attachment.id);

      // Preview
      const $preview = $wrap.find('.campos-media-preview');
      if (attachment.type === 'image' && attachment.sizes) {
        const src =
          attachment.sizes.medium && attachment.sizes.medium.url
            ? attachment.sizes.medium.url
            : attachment.url;
        $preview.html(
          '<img src="' +
            src +
            '" style="max-width:320px;height:auto;border:1px solid #eee;border-radius:8px;">',
        );
      } else {
        $preview.html('<code>' + attachment.url + '</code>');
      }
    });

    frame.open();
  });

  $(document).on('click', '.campos-media-remove', function (e) {
    e.preventDefault();
    const target = $(this).data('target');
    const $wrap = $(this).closest('.campos-media-field');
    $('#' + target).val('');
    $wrap.find('.campos-media-preview').html('');
  });

  // REPEATER: add/remove + sincroniza hidden store
  function syncRepeater($rep) {
    const fieldId = $rep.data('field-id');
    const data = [];

    $rep.find('.campos-repeater-row').each(function () {
      const row = {};
      $(this)
        .find('input, textarea, select')
        .each(function () {
          const name = $(this).attr('name') || '';
          // name = parent[IDX][sub]
          const m = name.match(/\[(.+?)\]\[(.+?)\]$/);
          if (!m) return;
          const subKey = m[2];
          row[subKey] = $(this).val();
        });
      data.push(row);
    });

    $rep.find('.campos-repeater-store').val(JSON.stringify(data));
  }

  $(document).on('click', '.campos-repeater-add', function () {
    const $rep = $(this).closest('.campos-repeater');
    const $tpl = $rep.find('.campos-repeater-template')[0];
    const html = $tpl.innerHTML.replaceAll('__i__', String(Date.now()));
    $rep.find('.campos-repeater-items').append(html);
    syncRepeater($rep);
  });

  $(document).on('click', '.campos-repeater-remove', function () {
    const $rep = $(this).closest('.campos-repeater');
    $(this).closest('.campos-repeater-row').remove();
    syncRepeater($rep);
  });

  $(document).on(
    'input change',
    '.campos-repeater input, .campos-repeater textarea, .campos-repeater select',
    function () {
      const $rep = $(this).closest('.campos-repeater');
      syncRepeater($rep);
    },
  );

  // GALLERY (múltiplas imagens)
  let galleryFrame;

  $(document).on('click', '.campos-gallery-select', function (e) {
    e.preventDefault();

    const target = $(this).data('target');
    const $wrap = $(this).closest('.campos-gallery');
    const max = parseInt($wrap.data('max') || '0', 10);
    const $input = $('#' + target);

    galleryFrame = wp.media({
      title: 'Selecionar imagens',
      button: { text: 'Usar imagens' },
      multiple: true,
      library: { type: 'image' },
    });

    galleryFrame.on('select', function () {
      const selection = galleryFrame.state().get('selection').toJSON();
      let ids = selection.map((a) => a.id);

      // merge com já existentes
      const current = ($input.val() || '')
        .split(',')
        .filter(Boolean)
        .map((x) => parseInt(x, 10));
      ids = current.concat(ids);

      // remove duplicados mantendo ordem
      ids = ids.filter((id, idx) => ids.indexOf(id) === idx);

      // aplica limite (se tiver)
      if (max > 0) ids = ids.slice(0, max);

      $input.val(ids.join(','));

      // atualiza preview
      const $grid = $wrap.find('.campos-gallery-grid');
      $grid.empty();

      ids.forEach((id) => {
        // tenta pegar thumb direto do objeto selecionado; se não tiver, usa URL completa
        const obj = selection.find((x) => x.id === id);
        const url =
          obj && obj.sizes && obj.sizes.thumbnail ? obj.sizes.thumbnail.url : obj ? obj.url : '';
        if (!url) return;

        $grid.append(`
        <div class="campos-gallery-item" data-id="${id}" style="position:relative;border:1px solid #eee;border-radius:8px;padding:6px;">
          <img src="${url}" style="display:block;max-width:150px;height:auto;">
          <button type="button" class="button campos-gallery-remove" style="position:absolute;top:6px;right:6px;">×</button>
        </div>
      `);
      });
    });

    galleryFrame.open();
  });

  $(document).on('click', '.campos-gallery-remove', function () {
    const $item = $(this).closest('.campos-gallery-item');
    const id = parseInt($item.data('id'), 10);
    const $wrap = $(this).closest('.campos-gallery');
    const $input = $wrap.find('input[type="hidden"]');

    let ids = ($input.val() || '')
      .split(',')
      .filter(Boolean)
      .map((x) => parseInt(x, 10));
    ids = ids.filter((x) => x !== id);

    $input.val(ids.join(','));
    $item.remove();
  });

  $(document).on('click', '.campos-gallery-clear', function (e) {
    e.preventDefault();
    const target = $(this).data('target');
    const $wrap = $(this).closest('.campos-gallery');

    $('#' + target).val('');
    $wrap.find('.campos-gallery-grid').empty();
  });
});
