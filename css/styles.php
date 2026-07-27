<?php ?>

/* ==========================================================
   DBViewer
   Theme
   Inspired by dreiling.dev
========================================================== */

/* ==========================================================
   Variables
========================================================== */

:root {
    --dbv-bg: #000010;
    --dbv-surface: #1e182c;
    --dbv-surface-light: #2a243a;
    --dbv-surface-hover: #483e62;
    --dbv-accent: #aa88ff;
    --dbv-accent-dark: #8866ff;
    --dbv-text: whitesmoke;
    --dbv-heading: beige;
    --dbv-text-muted: #b8b2c8;
    --dbv-border: #4b4465;
    --dbv-radius: 10px;
    --dbv-transition: .2s ease;
    --dbv-shadow: 0 0 0 1px rgba(170,136,255,.08);
}


/* ==========================================================
   Reset
========================================================== */

*,
*::before,
*::after {
    box-sizing: border-box;
}


/* ==========================================================
   Page
========================================================== */

body {
    margin: 0;
    padding: 2rem;
    background: var(--dbv-bg);
    color: var(--dbv-text);
    font-family: "Share Tech Mono", monospace;
}


a {
    color: var(--dbv-accent);
    text-decoration: none;
    transition: color var(--dbv-transition);
}

a:hover {
    color: white;
}


/* ==========================================================
   Typography
========================================================== */

.dbv-title,
.dbv-subtitle,
h1,
h2,
h3,
h4,
h5,
h6 {
    margin-top: 0;
    color: var(--dbv-heading);
    font-family: "Oxanium", sans-serif;
    font-weight: 600;
    letter-spacing: .05em;
}

.dbv-subtitle {
    font-size: .95rem;
    color: var(--dbv-text-muted);
}

.dbv-text-muted {
    color: var(--dbv-text-muted);
}

.dbv-text-accent {
    color: var(--dbv-accent);
}


/* ==========================================================
   Layout
========================================================== */

.dbv-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.dbv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1rem;
}

.dbv-field {
    display: flex;
    flex-direction: column;
    gap: .4rem;
}

.dbv-container {
    width: min(1500px, 95%);
    margin-inline: auto;
}

.dbv-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.dbv-toolbar-left,
.dbv-toolbar-right {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
}


/* ==========================================================
   Cards
========================================================== */

.dbv-card {
    padding: 1rem;
    margin-bottom: 1rem;
    background: var(--dbv-surface-light);
    border: 1px solid var(--dbv-border);
    border-radius: var(--dbv-radius);
    box-shadow: var(--dbv-shadow);
}


/* ==========================================================
   Metadata
========================================================== */

.dbv-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.dbv-meta-item {
    display: flex;
    flex-direction: column;
    gap: .25rem;
}

.dbv-meta-label {
    color: var(--dbv-text-muted);
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.dbv-meta-value {
    color: var(--dbv-heading);
    font-weight: bold;
}


/* ==========================================================
   Table
========================================================== */

.dbv-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--dbv-surface-light);
    border-radius: var(--dbv-radius);
    overflow: hidden;
}

.dbv-table thead {
    background: var(--dbv-surface);
}

.dbv-table th {
    color: var(--dbv-accent);
    text-align: left;
    font-weight: 600;
}

.dbv-table th,
.dbv-table td {
    padding: .8rem 1rem;
    border-bottom: 1px solid var(--dbv-border);
}

.dbv-table tbody tr {
    transition: background var(--dbv-transition);
}

.dbv-table tbody tr:hover {
    background: var(--dbv-surface-hover);
    cursor: pointer;
}

.dbv-table tbody tr:last-child td {
    border-bottom: none;
}


/* ==========================================================
   Forms
========================================================== */

.dbv-input,
.dbv-select {
    padding: .65rem .85rem;
    background: var(--dbv-surface);
    color: var(--dbv-text);
    border: 1px solid var(--dbv-border);
    border-radius: 6px;
    font-family: inherit;
    transition: border-color var(--dbv-transition);
}

.dbv-input:focus,
.dbv-select:focus {
    outline: none;
    border-color: var(--dbv-accent);
}


/* ==========================================================
   Buttons
========================================================== */

.dbv-button {
    padding: .65rem 1rem;
    background: var(--dbv-surface);
    color: var(--dbv-heading);
    border: 1px solid var(--dbv-accent-dark);
    border-radius: 7px;
    cursor: pointer;
    font-family: inherit;
    transition:
        background var(--dbv-transition),
        border-color var(--dbv-transition),
        transform var(--dbv-transition);
}

.dbv-button:hover {
    background: var(--dbv-surface-hover);
    border-color: var(--dbv-accent);
    transform: translateY(-1px);
}

.dbv-button:active {
    transform: translateY(0);
}


/* ==========================================================
   Status
========================================================== */

.dbv-success {
    color: #7ee787;
}

.dbv-error {
    color: #ff7b72;
}

.dbv-warning {
    color: #f2cc60;
}


/* ==========================================================
   Utilities
========================================================== */

.dbv-hidden {
    display: none;
}

.dbv-w-100 {
    width: 100%;
}

.dbv-mt-1 {
    margin-top: 1rem;
}

.dbv-mb-1 {
    margin-bottom: 1rem;
}

.dbv-text-center {
    text-align: center;
}

.dbv-text-right {
    text-align: right;
}


/* ==========================================================
   Scrollbar
========================================================== */

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--dbv-surface);
}

::-webkit-scrollbar-thumb {
    background: var(--dbv-surface-hover);
    border-radius: 999px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--dbv-accent-dark);
}


/* ==========================================================
   Responsive
========================================================== */

@media (max-width: 768px) {

    body {
        padding: 1rem;
    }

    .dbv-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .dbv-meta {
        flex-direction: column;
        gap: 1rem;
    }

}