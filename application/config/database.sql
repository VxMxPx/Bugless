-- Bugless Default Database Schema

-- STATEMENT: Make sure we have our version of users table.
DROP TABLE IF EXISTS 'users';

-- STATEMENT: Users Table, Set By Session Plug
-- We added activation_key, which is send to user on registration.
CREATE TABLE IF NOT EXISTS 'users' (
	'id'				INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'uname'				VARCHAR(200)						NOT NULL,
	'password'			TEXT								NOT NULL,
	'active'			INTEGER(1)							NOT NULL,
	'activation_key'	VARCHAR(255)						NOT NULL
);

-- STATEMENT: Sessions Table, Set By Session Plug
-- No changes.
CREATE TABLE IF NOT EXISTS 'users_sessions' (
	'id'		VARCHAR(255)	NOT NULL,
	'user_id'	VARCHAR(255)	NOT NULL,
	'ip'		VARCHAR(16)		NOT NULL,
	'agent'		VARCHAR(255)	NOT NULL,
	'expires'	INTEGER(12)		NOT NULL
);

-- STATEMENT: Additional User's Informations
CREATE TABLE IF NOT EXISTS 'users_details' (
	'user_id'		INTEGER			NOT NULL,
	'created_on'	INTEGER			NOT NULL,
	'updated_on'	INTEGER			NOT NULL,
	'full_name'		VARCHAR(255)		NULL,
	'timezone'		VARCHAR(255)	NOT NULL,
	'language'		VARCHAR(4)		NOT NULL,
	'additional'	TEXT				NULL
);

-- STATEMENT: User's Permissions
-- User for general permission project id will be set to 0.
-- For non-register users the user_id will be set to 0.
CREATE TABLE IF NOT EXISTS 'users_permissions' (
	'user_id'		INTEGER		NOT NULL,
	'project_id'	INTEGER		NOT NULL,
	'action'		VARCHAR		NOT NULL,
	'allowed'		INTEGER(1)	NOT NULL
);

-- STATEMENT: Settings
-- Settings which don't apply to any particular project,
-- will have project's ID set to 0.
CREATE TABLE IF NOT EXISTS 'settings' (
	'project_id'	INTEGER	NOT NULL,
	'key'			VARCHAR	NOT NULL,
	'value'			TEXT		NULL
);

-- STATEMENT: Tags
-- Particular tag might be added to project or bug,
-- when tag is added only to project, the "bug_id" will be set to 0,
-- when tag is added to bug, both "project_id" and "bug_id" will have value.
CREATE TABLE IF NOT EXISTS 'tags' (
	'id'			INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'project_id'	INTEGER								NOT NULL,
	'bug_id'		INTEGER								NOT NULL,
	'title'			VARCHAR								NOT NULL,
	'color'			VARCHAR								NOT NULL
);

-- STATEMENT: Projects
-- Status might be (for now) only 0 (inactive / closed) and 1 (open / active).
CREATE TABLE IF NOT EXISTS 'projects' (
	'id'			INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'title'			VARCHAR								NOT NULL,
	'created_on'	INTEGER								NOT NULL,
	'status'		INTEGER								NOT NULL
);

-- STATEMENT: Bug
-- Note that bug's ID isn't unique, so there can be more bugs with same id.
-- When selecting bug we must always provide a bug's "id" and a project ID!
-- The field "user_id" represent user who added this bug,
-- If it was anonymous then value will be 0.
-- The type can be 1: bug, 2: blueprint
CREATE TABLE IF NOT EXISTS 'bugs' (
	'id'			INTEGER	NOT NULL,
	'project_id'	INTEGER	NOT NULL,
	'milestone_id'	INTEGER		NULL,
	'user_id'		INTEGER	NOT NULL,
	'created_on'	INTEGER	NOT NULL,
	'updated_on'	INTEGER	NOT NULL,
	'priority'		INTEGER	NOT NULL,
	'status'		INTEGER	NOT NULL,
	'title'			VARCHAR	NOT NULL,
	'type'			INTEGER	NOT NULL,
	'body_raw'		TEXT	NOT NULL,
	'body_html'		TEXT	NOT NULL
);

-- STATEMENT: One bug can have more users assigned to.
-- This future is planned to be added, so database must be prepared.
-- For now we use relationship one-to-one, but it soon will be one-to-many.
CREATE TABLE IF NOT EXISTS 'bugs_to_users' (
	'bug_id'		INTEGER	NOT NULL,
	'project_id'	INTEGER	NOT NULL,
	'user_id'		INTEGER	NOT NULL
);

-- STATEMENT: Comments for particular bug
-- The field "user_id" represent user who added this comment,
-- If it was anonymous then value will be 0.
CREATE TABLE IF NOT EXISTS 'comments' (
	'id'			INTEGER	NOT NULL,
	'bug_id'		INTEGER	NOT NULL,
	'project_id'	INTEGER	NOT NULL,
	'user_id'		INTEGER	NOT NULL,
	'created_on'	INTEGER	NOT NULL,
	'body_html'		TEXT	NOT NULL
);

-- STATEMENT: Starred items
-- Collection of all items starred by user(s),
-- user can star following items:
-- bugs, projects, comments
CREATE TABLE IF NOT EXISTS 'starred' (
	'user_id'		INTEGER	NOT NULL,
	'project_id'	INTEGER	NOT NULL,
	'bug_id'		INTEGER		NULL,
	'comment_id'	INTEGER		NULL
);

-- STATEMENT: Milestones
-- Has unique IDs accross the system
CREATE TABLE IF NOT EXISTS 'milestones' (
	'id'			INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'project_id'	INTEGER								NOT NULL,
	'created_on'	INTEGER								NOT NULL,
	'due_date'		INTEGER								NOT NULL,
	'title'			VARCHAR								NOT NULL,
	'description'	VARCHAR									NULL
);

-- STATEMENT: Pages
-- Every page has unique ID. Pages are selected by handle.
-- The field "body_html" contains the html of last revision.
CREATE TABLE IF NOT EXISTS 'pages' (
	'id'			INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'parent_id'		INTEGER								NOT NULL,
	'project_id'	INTEGER								NOT NULL,
	'created_on'	INTEGER								NOT NULL,
	'updated_on'	INTEGER								NOT NULL,
	'handle'		VARCHAR								NOT NULL,
	'title'			VARCHAR								NOT NULL,
	'body_html'		TEXT								NOT NULL
);

-- STATEMENT: The revisions of pages
-- Revisions don't need HTML parsed body.
CREATE TABLE IF NOT EXISTS 'pages_revisions' (
	'id'			INTEGER	PRIMARY KEY	AUTOINCREMENT	NOT NULL,
	'page_id'		INTEGER								NOT NULL,
	'created_on'	INTEGER								NOT NULL,
	'user_id'		INTEGER								NOT NULL,
	'body_raw'		TEXT								NOT NULL
);

-- STATEMENT: Insert defaults into settings
INSERT INTO settings ('project_id','key','value')  VALUES (0, 'installed', '%BUGLESS_VERSION||%DATETIME');
