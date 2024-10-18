# Admin Panel (Vue.js Single Page Application)

Vue.js is an open-source model–view–viewmodel front end JavaScript framework for building user interfaces and single-page applications.

## Development Server

For the Vuejs Admin Panel SPA start the app container by running:

```shell
docker exec -it node /bin/bash
```

Then in folder within the node container **app/client** run the following commands:

```shell
npm install && npm run dev && npm run dev
```

## Production

Build the application for production:

```bash
npm install && npm run build
```
