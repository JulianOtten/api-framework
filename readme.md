idea:
self documenting endpoints using the folder/files classes
then searching for all the route methods 
and reading back the lines */ till /** (from up to down)
then looking for like an @api tag or something like that, that looks at all the information, including the route etc for parameters 
result should be cached based on modified times of the files



#modals

modals should be as easy to use as something like:

User extends Modal {
    __construct() {
        return $this->addInt('id', 10)
            ->addVarchar('username', 60)
            ->addDate('last_logged_in', DB::CURRENT_TIMESTMAP)
            ->addForeign('post_id', new Post)
    }
}


rework routing to work with attributes instead of a router class itself
this will allow for middleware implementations
this also allows for defining which payload we EXPECT and which payload we SEND



# new idea
have a resources folder, which is devided in sub folders containing the resource
this can be auto loaded through vendor autoload
in the resource folder, you have a resource controller. this will register some key classes you need
something like routes, seeder, interface etc
structure might look like:
- resources
  - ingredients
    - ingredientsController - extends base controller
    - ingredientInterface
    - ingredientSeeder
    - ingredientRoutes

the base controller should implement an interface that will have methods to retrieve all these special classes
if a class is not available, it should be null instead

then in a global app class, we register each resource, only pointing to the resource controller.
all of this can now be attribute driven (routes get their route attributes, interfaces their database/json attributes, and seeders their respective logic attributes (Like if a seeding job is required or for testing purposes, as a small example))