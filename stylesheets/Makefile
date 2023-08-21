all: compile watch

compile:
	pysassc --sourcemap src/screen.scss dist/screen.css

watch:
	find -name \*.scss | entr make compile
