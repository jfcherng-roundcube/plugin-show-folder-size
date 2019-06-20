/* eslint-env node */

'use strict';

module.exports = {
  comments: false,
  presets: [
    [
      '@babel/preset-env', {
        // https://github.com/webpack/webpack/issues/4039#issuecomment-419284940
        modules: 'commonjs',
        useBuiltIns: 'usage',
        corejs: 'core-js@3',
        // compile for what browsers?
        targets: 'ie 11',
      },
    ],
  ],
  plugins: [
    '@babel/transform-runtime',
  ],
};
