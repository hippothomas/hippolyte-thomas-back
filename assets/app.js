// Style
// ==================
import './scss/app.scss';

// Imports
// ==================
import { loadScripts } from './js/functions';
import { pictures } from './js/pictures';
import { project } from './js/project';
import { apikey } from "./js/apikey";
import { post } from "./js/post";

// Load Scripts
// ==================
loadScripts('project', project);
loadScripts('edit-btn', pictures);
loadScripts('api-keys', apikey);
loadScripts('post', post);
