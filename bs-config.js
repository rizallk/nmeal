module.exports = {
  proxy: 'http://localhost:8080',
  files: [
    'app/Views/**/*.php',
    'app/Controllers/**/*.php',
    'app/Models/**/*.php',
    'public/**/*.{css,js}',
  ],
  watch: true,
  open: false,
  notify: false,
  ghostMode: false,
  snippetOptions: {
    rule: {
      match: /<\/body>/i,
      fn: (snippet, match) => snippet + match,
    },
  },
};
