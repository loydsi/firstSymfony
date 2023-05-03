<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        //Afficher notre tableau de todo
        //si j'ai mon tableau de todo dans ma session je ne fait que l'afficher
        if(!$session->has(name:'todos')) {
            $todos = [
                'achat'=>'acheter clé usb',
                'cours'=>'Finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash(type: 'info', message:"La liste des todos viens d'être initialisée");
        }
        return $this->render(view: 'todo/index.html.twig');
    }

    #[Route(
        '/add/{name?Lundi}/{content?symfony}',
         name: 'todo.add',
         )]
    public function addTodo(Request $request, $name, $content): RedirectResponse {
        $session = $request->getSession();
        // vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) {
            //Si oui
            // vérifier si on à déjà un todo avec le meme name
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash(type: 'error', message:"Le todo d'id $name existe déjà dans la liste");
            } else {
            // sinon on l'ajoute et on affiche un message de succés
            $todos[$name] = $content;
            $this->addFlash(type: 'success', message:"Le todo d'id $name a été ajouté avec succés");
            $session->set('todos', $todos);
            }
        } else {
            // si non
            // afficher une erreur et on va rediriger vers le controller initial
            $this->addFlash(type: 'error', message:"La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute(route: 'todo');        
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse {
        $session = $request->getSession();
        // vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) {
            //Si oui
            // vérifier si on à déjà un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash(type: 'error', message:"Le todo d'id $name n'existe pas dans la liste");
            } else {
            // sinon on l'ajoute et on affiche un message de succés
            $todos[$name] = $content;
            $session->set('todos', $todos);
            $this->addFlash(type: 'success', message:"Le todo d'id $name a été modifié avec succés");
            }
        } else {
            // si non
            // afficher une erreur et on va rediriger vers le controller initial
            $this->addFlash(type: 'error', message:"La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute(route: 'todo');        
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse {
        $session = $request->getSession();
        // vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) {
            //Si oui
            // vérifier si on à déjà un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash(type: 'error', message:"Le todo d'id $name n'existe pas dans la liste");
            } else {
            // sinon on l'ajoute et on affiche un message de succés
            unset($todos[$name]);
            $session->set('todos', $todos);
            $this->addFlash(type: 'success', message:"Le todo d'id $name a été supprimé avec succés");
            }
        } else {
            // si non
            // afficher une erreur et on va rediriger vers le controller initial
            $this->addFlash(type: 'error', message:"La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute(route: 'todo');        
    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse {
        $session = $request->getSession();
        $session->remove(name: 'todos');
        return $this->redirectToRoute(route: 'todo');        
    }
}
