<div class="mikhmon-mod-footer">
  <span>MIKHMON v3.20 Patched (Multi-ROS)</span>
  <span class="separator">&bull;</span>
  <span>mod by <a href="https://agil.web.id" target="_blank">agil</a></span>
  <span class="separator">&bull;</span>
  <span class="private-use">untuk kalangan sendiri</span>
</div>

<style>
  .mikhmon-mod-footer {
    position: fixed;
    bottom: 12px;
    right: 20px;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.35);
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    background: rgba(12, 11, 24, 0.6);
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  .mikhmon-mod-footer a {
    color: rgba(99, 102, 241, 0.85);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
  }
  .mikhmon-mod-footer a:hover {
    color: rgba(110, 114, 245, 1);
    text-decoration: underline;
  }
  .mikhmon-mod-footer .private-use {
    font-style: italic;
    color: rgba(255, 255, 255, 0.25);
  }
  .mikhmon-mod-footer .separator {
    color: rgba(255, 255, 255, 0.15);
  }
  
  /* Responsive positioning - selalu tampil di tengah bawah */
  @media (max-width: 768px) {
    .mikhmon-mod-footer {
      position: fixed;
      bottom: 10px;
      right: auto;
      left: 50%;
      transform: translateX(-50%);
      white-space: nowrap;
      font-size: 10px;
      padding: 5px 10px;
      gap: 4px;
    }
  }

  @media (max-width: 480px) {
    .mikhmon-mod-footer {
      font-size: 9.5px;
      padding: 4px 8px;
      bottom: 8px;
      gap: 3px;
    }
  }
</style>
