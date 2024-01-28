const mix = require('laravel-mix');
const path = require('path');

const baseAsset = path.resolve(__dirname, '');
const baseStyles = baseAsset + '/styles';
const basePublish = baseAsset + '/public';

mix.styles(
    [
        baseStyles + '/css/frontend/user_credit.css',
    ],
    `${basePublish}/css/frontend/user_credit.min.css`
);
