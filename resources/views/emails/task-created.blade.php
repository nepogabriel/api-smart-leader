@component('mail::message')
    # Nova Tarefa Cadastrada

    Olá {{ $user->name }},

    Uma nova tarefa foi criada com sucesso! Aqui estão os detalhes:

    - **Título**: {{ $task->title }}
    - **Descrição**: {{ $task->description ?: 'Sem descrição' }}
    - **Status**: {{ $task->status }}
    - **Prioridade**: {{ $task->priority }}
    - **Data de Vencimento**: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : 'Não definida' }}

    Acesse o sistema para visualizar ou gerenciar esta tarefa.

    Obrigado,  
    **Equipe Smart Leader**
@endcomponent