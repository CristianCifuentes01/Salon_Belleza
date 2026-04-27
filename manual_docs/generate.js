const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function run() {
    const browser = await puppeteer.launch({ headless: 'new' });
    const screenshots = {};

    // --- CLIENTE ---
    const ctxCliente = await browser.createBrowserContext();
    const pageCliente = await ctxCliente.newPage();
    await pageCliente.setViewport({ width: 1280, height: 800 });

    console.log('Navegando a la página principal...');
    await pageCliente.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle2' });
    await pageCliente.screenshot({ path: '1_inicio.png' });
    screenshots['inicio'] = '1_inicio.png';

    console.log('Navegando al login...');
    await pageCliente.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle2' });
    await pageCliente.screenshot({ path: '2_login.png' });
    screenshots['login'] = '2_login.png';

    console.log('Iniciando sesión como cliente...');
    await pageCliente.type('input[name="email"]', 'cliente@appsalon.com');
    await pageCliente.type('input[name="password"]', 'password');
    await Promise.all([
        pageCliente.waitForNavigation({ waitUntil: 'networkidle2' }),
        pageCliente.click('button[type="submit"]')
    ]);
    
    // Esperar a que la página cargue bien el dashboard
    await new Promise(r => setTimeout(r, 1000));
    await pageCliente.screenshot({ path: '3_cliente_dashboard.png' });
    screenshots['cliente_dashboard'] = '3_cliente_dashboard.png';

    console.log('Navegando a nueva cita...');
    await pageCliente.goto('http://127.0.0.1:8000/citas/create', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 1000));
    await pageCliente.screenshot({ path: '4_nueva_cita.png' });
    screenshots['nueva_cita'] = '4_nueva_cita.png';

    await pageCliente.close();
    await ctxCliente.close();

    // --- ADMIN ---
    const ctxAdmin = await browser.createBrowserContext();
    const pageAdmin = await ctxAdmin.newPage();
    await pageAdmin.setViewport({ width: 1280, height: 800 });

    console.log('Iniciando sesión como administrador...');
    await pageAdmin.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle2' });
    await pageAdmin.type('input[name="email"]', 'admin@appsalon.com');
    await pageAdmin.type('input[name="password"]', 'password');
    await Promise.all([
        pageAdmin.waitForNavigation({ waitUntil: 'networkidle2' }),
        pageAdmin.click('button[type="submit"]')
    ]);
    
    await new Promise(r => setTimeout(r, 1000));
    await pageAdmin.screenshot({ path: '5_admin_dashboard.png' });
    screenshots['admin_dashboard'] = '5_admin_dashboard.png';

    console.log('Navegando a gestión de citas (Admin)...');
    await pageAdmin.goto('http://127.0.0.1:8000/admin/citas', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 1000));
    await pageAdmin.screenshot({ path: '6_admin_citas.png' });
    screenshots['admin_citas'] = '6_admin_citas.png';

    console.log('Navegando a reportes (Admin)...');
    await pageAdmin.goto('http://127.0.0.1:8000/admin/reportes', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 1000));
    await pageAdmin.screenshot({ path: '7_admin_reportes.png' });
    screenshots['admin_reportes'] = '7_admin_reportes.png';

    await pageAdmin.close();
    await ctxAdmin.close();

    console.log('Generando PDF...');
    
    // Generar PDF usando el browser principal
    const pagePdf = await browser.newPage();
    function imgToBase64(file) {
        const bitmap = fs.readFileSync(path.join(__dirname, file));
        return Buffer.from(bitmap).toString('base64');
    }

    const htmlContent = `
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; color: #333; line-height: 1.6; }
            h1 { color: #4F46E5; text-align: center; border-bottom: 2px solid #4F46E5; padding-bottom: 10px; }
            h2 { color: #3730A3; margin-top: 30px; }
            p { font-size: 16px; margin-bottom: 20px; }
            .screenshot { width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; page-break-inside: avoid; }
            .step { margin-bottom: 50px; }
            .page-break { page-break-before: always; }
        </style>
    </head>
    <body>
        <h1>Manual de Usuario - AppSalon</h1>
        <p>Bienvenido al manual de usuario de AppSalon. Este documento te guiará paso a paso por las funcionalidades principales del sistema, tanto para clientes como para administradores.</p>
        
        <div class="step">
            <h2>1. Página Principal (Inicio)</h2>
            <p>Al ingresar a la aplicación, verás la página principal que muestra los servicios destacados del salón. Desde aquí puedes acceder al inicio de sesión o registro.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['inicio'])}" class="screenshot" alt="Inicio">
        </div>

        <div class="step">
            <h2>2. Iniciar Sesión</h2>
            <p>Para acceder a tus citas o al panel de administración, debes iniciar sesión con tus credenciales.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['login'])}" class="screenshot" alt="Login">
        </div>

        <div class="page-break"></div>
        
        <h1>Sección de Clientes</h1>
        <div class="step">
            <h2>3. Panel del Cliente</h2>
            <p>Una vez iniciada la sesión, los clientes verán su panel de control con el historial de sus citas recientes y próximas.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['cliente_dashboard'])}" class="screenshot" alt="Panel Cliente">
        </div>

        <div class="step">
            <h2>4. Agendar una Nueva Cita</h2>
            <p>En la sección "Nueva Cita", los clientes pueden seleccionar los servicios que desean, y escoger una fecha y hora disponible para su visita.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['nueva_cita'])}" class="screenshot" alt="Nueva Cita">
        </div>

        <div class="page-break"></div>

        <h1>Sección de Administradores</h1>
        <div class="step">
            <h2>5. Panel de Administración</h2>
            <p>Los usuarios con rol de administrador tienen acceso a un panel exclusivo donde pueden ver estadísticas generales, cantidad de citas del día y total de usuarios.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['admin_dashboard'])}" class="screenshot" alt="Panel Admin">
        </div>

        <div class="step">
            <h2>6. Gestión de Citas</h2>
            <p>En la gestión de citas, el administrador puede revisar todas las citas agendadas, filtrar por fechas y cambiar su estado (Pendiente, Completada, Cancelada).</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['admin_citas'])}" class="screenshot" alt="Admin Citas">
        </div>

        <div class="page-break"></div>

        <div class="step">
            <h2>7. Reportes</h2>
            <p>En la sección de reportes, el administrador puede visualizar las estadísticas de citas y descargar reportes detallados en formato CSV para su análisis.</p>
            <img src="data:image/png;base64,${imgToBase64(screenshots['admin_reportes'])}" class="screenshot" alt="Admin Reportes">
        </div>

    </body>
    </html>
    `;

    await pagePdf.setContent(htmlContent);
    await pagePdf.pdf({ 
        path: 'Manual_Usuario_AppSalon.pdf', 
        format: 'A4',
        printBackground: true,
        margin: { top: '20px', right: '20px', bottom: '20px', left: '20px' }
    });

    console.log('¡PDF generado exitosamente!');
    await browser.close();
}

run().catch(console.error);
