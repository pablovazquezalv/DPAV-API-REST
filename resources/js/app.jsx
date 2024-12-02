import React from 'react';
import { createRoot } from 'react-dom/client';
import { InertiaApp } from '@inertiajs/inertia-react';

const el = document.getElementById('app');
const app = JSON.parse(el.dataset.page);

createRoot(el).render(
  <InertiaApp
    initialPage={app}
    resolveComponent={name => import(`./Pages/${name}`).then(module => module.default)}
  />
);
