# 📁 README.md - VERSIÓN MINIMALISTA (COPIA Y PEGA TODO)

```markdown
# 🏛️ SISTEMA DE ARCHIVO FISCAL - MINISTERIO PÚBLICO

![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-10.4-blue?logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.1-purple?logo=bootstrap)

---

## 📖 DESCRIPCIÓN

Sistema web de 3 capas para la gestión de carpetas fiscales del Ministerio Público del Perú. Permite registrar, ubicar, prestar y controlar carpetas con alertas automáticas de vencimiento.

---

## ✨ CARACTERÍSTICAS

- ✅ Registro de carpetas fiscales
- ✅ Carga masiva desde Excel/CSV
- ✅ Consulta de ubicación ("No ubicado")
- ✅ Préstamo con guía única (PREST-XXX)
- ✅ Alertas automáticas de vencimiento
- ✅ Envío de notas por correo electrónico
- ✅ Reportes por dependencia e historial
- ✅ Multiusuario con roles y auditoría

---

## 🛠️ TECNOLOGÍAS

| Frontend | Backend | Base de Datos |
|----------|---------|---------------|
| HTML5, CSS3, Bootstrap 5 | PHP 8, PDO | MySQL |

---

## 🚀 INSTALACIÓN

```bash
# 1. Clonar repositorio
git clone https://github.com/74902142-star/Practica_Sist_Arc.git

# 2. Importar base de datos (phpMyAdmin)
sql/database.sql

# 3. Configurar conexión
cp config/database.example.php config/database.php

# 4. Acceder
http://localhost/Practica_Sist_Arc/
```

---

## 🔑 ACCESO

| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | `admin@mp.gob.pe` | `admin123` |
| Fiscal | `fiscal1@mp.gob.pe` | `admin123` |

---

## 👥 EQUIPO

**Práctica Ingeniería Web - Grupo 03**

| Integrante | Rol |
|------------|-----|
| Fernandez Condor Jhon Smith | Backend Developer |
| Gomez Toribio Geraldine Paola | Frontend Developer |
| Ccente Quispe Michel Frederick | Analista & QA |

**Docente:** Mag. Miguel Ángel Casimiro Bravo

**Institución:** Universidad Continental

---

## 📁 ESTRUCTURA

```
├── assets/         # CSS, JS
├── config/         # Conexión BD
├── controllers/    # Controladores PHP
├── models/         # Modelos de datos
├── views/          # Vistas (UI)
├── sql/            # Script BD
└── index.php       # Punto de entrada
```

---

## 📄 LICENCIA

Proyecto académico - Universidad Continental © 2026

---

<p align="center">⚖️ "La justicia no consiste en no cometer injusticias, sino en no tolerarlas" ⚖️</p>
```

---

## ✅ VERIFICACIÓN

Después de pegar este contenido en tu `README.md`:

1. Guarda el archivo
2. Súbelo a GitHub:

```bash
git add README.md
git commit -m "📝 Agregar README minimalista con integrantes"
git push origin master
```
