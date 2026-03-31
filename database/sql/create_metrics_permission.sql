-- Script para crear el permiso 'metrics.create' y asignarlo al rol Admin
-- Base de datos: management_control

USE management_control;

-- 1. Crear el permiso (asignándolo al módulo Home)
SET @module_id = (SELECT id FROM modules WHERE slug = 'home' LIMIT 1);

INSERT INTO permissions (id, name, slug, module_id, created_at, updated_at)
VALUES (
    UUID(),
    'Crear Consultas de Métricas',
    'metrics.create',
    @module_id,
    NOW(),
    NOW()
);

-- 2. Asignar el permiso al rol Admin
SET @permission_id = (SELECT id FROM permissions WHERE slug = 'metrics.create');

INSERT INTO permission_role (permission_id, role_id)
SELECT @permission_id, id FROM roles WHERE name = 'Admin';

-- Verificar que se creó correctamente
SELECT
    p.id,
    p.name,
    p.slug,
    r.name as role_name
FROM permissions p
LEFT JOIN permission_role pr ON p.id = pr.permission_id
LEFT JOIN roles r ON pr.role_id = r.id
WHERE p.slug = 'metrics.create';
