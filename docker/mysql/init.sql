CREATE DATABASE agenda_semanal IF NOT EXISTS CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE agenda_semanal;

-- 1. Usuários
CREATE TABLE users (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    email          VARCHAR(150) UNIQUE NOT NULL,
    password       VARCHAR(255) NOT NULL,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Colunas (Semana + Recorrentes)
CREATE TABLE columns (
    column_id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id  		INT NOT NULL,
    title      		VARCHAR(60) NOT NULL,       
    position       	INT NOT NULL,
    created_at   	DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Tarefas
CREATE TABLE tasks (
    task_id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id         	INT NOT NULL,
    column_id           INT NOT NULL,
    title               VARCHAR(150) NOT NULL,
    is_recurring        TINYINT(1) DEFAULT 0,
    week_days           VARCHAR(20) NULL,           -- Ex: "1,3,5" (seg,qua,sex)
    time_task                VARCHAR(20) NULL,
    active              TINYINT(1) DEFAULT 1,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (column_id)  REFERENCES columns(column_id) ON DELETE CASCADE
);

-- 4. Histórico de Execução (muito bom que você pensou nisso)
CREATE TABLE task_execution (
    exec_id          INT AUTO_INCREMENT PRIMARY KEY,
    task_id          INT NOT NULL,
    user_id          INT NOT NULL,
    exec_date        DATE NOT NULL,
    status           TINYINT(1) NOT NULL,        -- 1 = concluído, 0 = não
    exec_time 	     TIME NULL,
    created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (task_id)  REFERENCES tasks(task_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_execute (task_id, exec_date)
);

-- 5. Refresh Tokens (apenas uma tabela)
CREATE TABLE refresh_tokens (
    refresh_id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id    		  INT NOT NULL,
    refresh_token 	  VARCHAR(255) NOT NULL,
    expires_at    	  DATETIME NOT NULL,
    created_at     	  DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_expire (user_id, expires_at)
);