# Proyecto Flask + PHP + MySQL

Este proyecto combina una aplicación Flask con módulos PHP y una base de datos MySQL.

## 🔧 Requisitos

- Python 3.x instalado
- XAMPP (u otro servidor local para PHP/MySQL)
- Git (opcional)
- Navegador web

---

## 📁 Estructura del Proyecto

- `flask/` → Aplicación Flask (backend en Python)
- `php/` → Módulos en PHP
- `BD/` → Scripts o archivos SQL de base de datos
- `requirements.txt` → Lista de dependencias de Python
- `venv/` → (NO incluida) Entorno virtual que debe crearse localmente

---

## ⚙️ Pasos para configurar

### 1. Crear y activar entorno virtual (solo una vez)

Abre una terminal y navega a la carpeta raíz del proyecto:

```bash
cd ruta/a/la/carpeta/proyecto
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt


### 2. Desde la carpeta flask/, ejecuta:

python app.py


### 3.  Configurar base de datos
Inicia XAMPP y levanta MySQL y Apache.

Usa phpMyAdmin o consola para importar la base de datos desde el archivo .sql en la carpeta BD/.

### 4. Acceso
Flask API: http://localhost:5000

Módulos PHP: http://localhost/ArqTec/php/