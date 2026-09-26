/**
 * ACURIA - Main Application Entry Point
 * Arquitetura modular ES6, segura e performática.
 */

import { initNavigation } from './modules/navigation.js';
import { initSimulator } from './modules/simulator.js';
import { initFormHandler } from './modules/form.js';
import { initAnimations } from './modules/animations.js';

document.addEventListener('DOMContentLoaded', () => {
  try {
    initNavigation();
    initSimulator();
    initFormHandler();
    initAnimations();
    console.info('ACURIA Intelligence Platform initialized successfully.');
  } catch (error) {
    console.error('Initialization error:', error);
  }
});
