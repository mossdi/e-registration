**Первичная настройка после установки:**
- `php yii migrate --migrationPath=@yii/rbac/migrations/` - миграция для RBAC
- `php yii role/create-all-role` - добавление всех ролей из модели **User**
- `php yii role/assign-role "phone" "id"` - назначить роль пользователю