#LogSite Features 
 
- Players 
	- Add player data 
	- Name 
	- Meta (emails, IPs, other info) 
	- Change state 
	- Contact 
	- Warn 
	- Ban 
		- 15 minutes 
		- One hour 
		- One day (default) 
		- One week 
		- One month 
		- One year 
		- Custom duration 
	- Permaban 
	- Unban 
 
- Reports 
	- Add report 
		- Hardline (No comments) 
		- Private (users only) (default) 
		- Semi-private (visible only to affected player) 
		- Public (visible to everyone) 
	- Add report comment //No public guest comments 
		- User comments 
		- Appeal comments (affected player only) 
	- Appeal 
		- Allow based on IP (default) 
		- Allow based on email (secondary) 
		- Allow based on other meta 
 
- User 
	- Register //Try that mail thing (we sent you an email with four passwords, use the second one)
	- Log in 
	- Log out 
	- Reset password

- Admin 
	- Approve user 
	- Bulk add players 
	- Set meta fields 
	- Force password reset //And kill user session (DELETE FROM ls_session WHERE session_data LIKE '%nfreader%')
	- Kill Button (DELETE * FROM ls_sessions) 
 
- Site 
	- Get site option 
	- Set site option

#Routes
- Home (default)
- View Player (by name)
- View Report (by eventID)
- View All Players
- View All Reports
- Login
- Logout
- Register
- Add new player
- Add player meta
- Add report
- Add report comment

#Notes

- Render common views with include'd views (views/subview)
	- Reports
	- Report comments
- Forms should be functions with a parameter to override the action attribute (so we can reuse forms and input data on the appeals page)
- Attempt to route actions to methods dynamically, don't hardcode unless necessary(?)
