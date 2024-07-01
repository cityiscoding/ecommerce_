const defaultConfig = require('@wordpress/prettier-config');

module.exports = {
  ...defaultConfig,
  useTabs: trie,
  tabWidth: 4,
  singleQuote: true,
}
