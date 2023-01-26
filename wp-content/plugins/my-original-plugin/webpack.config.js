const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
// const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

module.exports = {
  devtool: 'source-map',
  entry: {
    main: './assets/src/js/index.js',
  },
  output: {
    path: path.resolve(__dirname, './assets/dist'),
    filename: 'js/[name].js',
    assetModuleFilename: 'images/[name][ext]',
    // publicPath: '/',
  },
  module: {
    rules: [
      {
        test: /\.(css|sass|scss)/, //testはファイル名(.css)を検知
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              esModule: false,
            },
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [['autoprefixer', { grid: true }]],
              },
            },
          },
          {
            loader: 'sass-loader',
            options: {
              implementation: require('sass'),
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.(jpe?g|png|svg)/,
        type: 'asset/resource',
        // generator: {
        //   filename: 'images/[name][ext]',
        // },
        use: [
          //{
          // loader: 'file-loader',
          // options: {
          //   esModule: false,
          //   name: 'images/[name].[ext]',
          // },
          //},
          // {
          //   loader: 'image-webpack-loader',
          //   options: {
          //     mozjpeg: {
          //       progressive: true,
          //       quality: 65,
          //     },
          //   },
          // },
        ],
      },
      // {
      //   test: /\.svg/,
      //   type: 'asset/inline',
      //   generator: {
      //     dataUrl: (content) => {
      //       content = content.toString();
      //       return svgToMiniDataURI(content);
      //     },
      //   },
      // },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './css/[name].css',
    }),

    new CleanWebpackPlugin(),
    new CopyPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, './assets/src/media'),
          to: path.resolve(__dirname, './assets/dist/media/[name]-min[ext]'),
        },
      ],
    }),
    new ImageMinimizerPlugin({
      test: /\.(jpe?g|png)$/i,
      minimizer: {
        implementation: ImageMinimizerPlugin.squooshMinify,
        options: {
          encodeOptions: {
            mozjpeg: {
              quality: 70,
            },
            oxipng: {
              level: 3,
              interlace: false,
            },
          },
        },
      },
    }),
  ],
};
