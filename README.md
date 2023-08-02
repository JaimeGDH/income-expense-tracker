# income-expense-tracker
Administrador simple de ingresos y egresos

## Instrucciones

Ejecutar:
```
npm run dev
php artisan serve
php artisan migrate
```

Si ya está creada la base de datos utilizar:
```
php artisan migrate:refresh
```

Las migraciones vienen con información mínima para su uso (categorías y tipo de transacción)

## Modelo de datos propuesto
![image](https://github.com/JaimeGDH/income-expense-tracker/assets/13523127/ee345252-b224-44f9-aad4-7a1e9aca79f8)

Para lo anterior se crearon las respectivas migraciones con las relaciones correspondientes

## Primeros pasos
Se puede registrar usando la tabla usuarios por defecto de Laravel
![image](https://github.com/JaimeGDH/income-expense-tracker/assets/13523127/43a835bb-4f7e-44c3-83e0-37e08a219bd0)

Una vez ingresado puede acceder a la sección de ingresos para visualizar los registros existentes y agregar nuevos
![image](https://github.com/JaimeGDH/income-expense-tracker/assets/13523127/aa77ee96-9cb9-4654-a5e9-7f87c127a29c)

![image](https://github.com/JaimeGDH/income-expense-tracker/assets/13523127/7e28d550-e53e-4ec0-a298-b499528f5b79)

En el resumen se podrá visualizar los montos por mes y año junto con un gráfico.
El idioma a mostrar se ajusta de acuerdo a la configuración del sistema.
![image](https://github.com/JaimeGDH/income-expense-tracker/assets/13523127/82a2a66d-1ceb-48b8-bad5-acb81efd6046)

## Herramientas
Laravel versión 10

PHP versión 8.1

Base de datos MySql versión 8.0.33

Bootstrap versiónbo 5

