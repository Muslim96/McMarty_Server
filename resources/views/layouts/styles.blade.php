<style>
    :root { --brand:#4f46e5; --brand-dark:#4338ca; --bg:#f4f6fb; --radius:14px; }
    body { font-family:'Inter',system-ui,sans-serif; background:var(--bg); color:#1f2937; }
    .sidebar { position:fixed; inset:0 auto 0 0; width:264px; background:linear-gradient(180deg,#111827,#1e1b4b); padding:20px 14px; display:flex; flex-direction:column; z-index:1040; transition:transform .25s; overflow-y:auto; }
    .brand { display:flex; align-items:center; gap:10px; color:#fff; font-size:1.3rem; text-decoration:none; padding:4px 10px 22px; }
    .brand i { background:var(--brand); border-radius:10px; width:38px; height:38px; display:grid; place-items:center; font-size:1.2rem; }
    .brand b { color:#a5b4fc; }
    .nav-title { color:#6b7280; font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; padding:16px 12px 6px; font-weight:600; }
    .sidebar .nav-link { color:#cbd5e1; border-radius:10px; padding:10px 12px; display:flex; align-items:center; gap:12px; font-weight:500; font-size:.93rem; }
    .sidebar .nav-link i { font-size:1.1rem; }
    .sidebar .nav-link:hover { background:rgba(255,255,255,.07); color:#fff; }
    .sidebar .nav-link.active { background:var(--brand); color:#fff; box-shadow:0 6px 16px rgba(79,70,229,.4); }
    .side-user { margin-top:auto; display:flex; gap:10px; align-items:center; padding:12px; background:rgba(255,255,255,.06); border-radius:12px; }
    .avatar { width:38px; height:38px; border-radius:50%; background:#6366f1; color:#fff; display:grid; place-items:center; font-weight:600; flex-shrink:0; }
    .content { margin-left:264px; padding:28px 32px; min-height:100vh; }
    .topbar { display:flex; align-items:center; gap:14px; margin-bottom:24px; }
    .page-title { font-size:1.55rem; font-weight:700; margin:0; }
    .card { border:0; border-radius:var(--radius); box-shadow:0 1px 3px rgba(16,24,40,.06),0 1px 2px rgba(16,24,40,.04); }
    .card-header { background:transparent; border-bottom:1px solid #eef0f5; font-weight:600; padding:16px 20px; }
    .btn { border-radius:10px; font-weight:500; }
    .btn-primary { background:var(--brand); border-color:var(--brand); }
    .btn-primary:hover { background:var(--brand-dark); border-color:var(--brand-dark); }
    .form-control, .form-select, .ts-control { border-radius:10px !important; border-color:#dfe3ec; padding:.6rem .85rem; }
    .form-control:focus, .form-select:focus { border-color:#a5b4fc; box-shadow:0 0 0 .25rem rgba(99,102,241,.15); }
    .form-label { font-weight:500; font-size:.88rem; color:#4b5563; }
    .table > :not(caption) > * > * { padding:.9rem 1rem; }
    .table thead th { font-size:.74rem; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; background:#f9fafb; border-bottom:0; font-weight:600; }
    .table tbody tr:hover { background:#fafbff; }
    .stat { display:flex; align-items:center; gap:16px; padding:20px; }
    .stat .icon { width:52px; height:52px; border-radius:14px; display:grid; place-items:center; font-size:1.5rem; }
    .stat .val { font-size:1.55rem; font-weight:700; line-height:1.1; }
    .badge { font-weight:500; padding:.45em .75em; border-radius:8px; }
    .ava-sm { width:38px; height:38px; border-radius:50%; background:#e0e7ff; color:#4338ca; display:grid; place-items:center; font-weight:600; font-size:.85rem; flex-shrink:0; }
    .logo-sm { width:42px; height:42px; border-radius:10px; object-fit:cover; background:#eef2ff; display:grid; place-items:center; color:#6366f1; font-size:1.2rem; flex-shrink:0; }
    .empty { padding:48px 20px; text-align:center; color:#9ca3af; }
    .empty i { font-size:2.4rem; display:block; margin-bottom:8px; }
    .sidebar-backdrop { display:none; }
    @media (max-width:991px) {
        .sidebar { transform:translateX(-100%); }
        .content { margin-left:0; padding:18px 14px; }
        body.menu-open .sidebar { transform:none; }
        body.menu-open .sidebar-backdrop { display:block; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1030; }
    }
</style>
