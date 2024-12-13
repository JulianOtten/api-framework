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