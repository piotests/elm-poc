// require('jest-fetch-mock').enableMocks();
// fetchMock.dontMock()
global.fetch = require('jest-fetch-mock');
// global.fetch = jest.fn()