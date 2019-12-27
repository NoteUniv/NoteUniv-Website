let r = tippy('a.tippy-note', {
  placement: 'right',
  arrow: false,
  trigger: 'click',
  maxWidth: 1000,
  theme: 'note',
  content(reference) {
    const id = reference.getAttribute('data-template');
    const template = document.getElementById(id);
    return template.innerHTML;
  }
});