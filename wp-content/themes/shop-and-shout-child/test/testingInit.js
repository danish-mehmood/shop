export const isDebugging = () => {
  let debugging_mode = {
    puppeteer: {
      headless: false,
      slowMo: 40,
      args: ['--window-size=1440,900']
    },
    jasmine: 300000
  };
  return process.env.NODE_ENV == "debug" ? debugging_mode : false;
};
