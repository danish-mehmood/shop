import faker from "faker";
import puppeteer from "puppeteer";
import { isDebugging } from "./testingInit.js";

const LOCAL = "http://localhost/shop-and-shout/influencer-signup/ishopandshout";

const user = {
  firstName: faker.name.firstName(),
  lastName: faker.name.lastName(),
  email: faker.internet.email(),
  password: faker.internet.password(),
}

let page;
let browser;
const width = 1920;
const height = 1080;

const devices = require('puppeteer/DeviceDescriptors');
const iPhoneX = devices['iPhone X'];

beforeAll(async () => {
  browser = await puppeteer.launch(isDebugging().puppeteer);
  page = await browser.newPage();
  await page.emulate(iPhoneX);
  // await page.setViewport({width,height});
});

afterAll(() => {
  browser.close();
});

describe("Signup form (email)", () => {
  test("user can submit a signup form and is redirected to /welcome", async() => {
    await page.goto(LOCAL);
    await page.waitForSelector("#inf-registration-form");
    await page.click("#inf_firstname");
    await page.type("#inf_firstname", user.firstName);
    await page.click("#inf_lastName");
    await page.type("#inf_lastName", user.lastName);
    await page.click("#inf_email");
    await page.type("#inf_email", user.email);
    await page.click("#inf_password");
    await page.type("#inf_password", user.password);
    await page.click("button[type=submit]");
    await page.waitForSelector('.welcome-to-sas-container');
  }, isDebugging()?isDebugging().jasmine:300000);
});

// describe("Signup form (facebook)", () => {
//   test("user can submit a signup form", async() => {
//     await page.goto(LOCAL);
//     await page.waitForSelector("#inf-registration-form");
//     await page.click("#inf_firstname");
//     await page.type("#inf_firstname", user.firstName);
//     await page.click("#inf_lastName");
//     await page.type("#inf_lastName", user.lastName);
//     await page.click("#inf_email");
//     await page.type("#inf_email", user.email);
//     await page.click("#inf_password");
//     await page.type("#inf_password", user.password);
//     await page.click("button[type=submit]");
//     await page.waitForSelector('.welcome-to-sas-container');
//   }, isDebugging()?isDebugging().jasmine:300000);
// });
