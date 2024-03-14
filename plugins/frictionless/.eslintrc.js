module.exports = {
  parser: '@typescript-eslint/parser',
  plugins: ['import'],
  extends: ['plugin:@typescript-eslint/recommended', 'prettier/@typescript-eslint', 'plugin:prettier/recommended'],
  rules: {
    '@typescript-eslint/no-use-before-define': ['error', { functions: false }],
    '@typescript-eslint/camelcase': ['error', { properties: 'never' }],
    '@typescript-eslint/interface-name-prefix': 'off',
    'import/order': [
      'error',
      {
        pathGroupsExcludedImportTypes: ['builtin'],
        pathGroups: [
          {
            pattern: '@homex/**',
            group: 'internal',
            position: 'after',
          },
          {
            pattern: '**',
            group: 'internal',
            position: 'after',
          },
        ],
        groups: ['builtin', ['external', 'internal'], 'parent', ['sibling', 'index']],
        'newlines-between': 'always',
      },
    ],
  },
};
