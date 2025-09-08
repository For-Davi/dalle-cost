<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Relatório de Divídas</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
      background-color: #f7f7f7;
      color: #333;
      padding: 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
      color: #444;
    }

    h2.member-title {
      margin-top: 40px;
      font-size: 20px;
      color: #006400; /* verde escuro */
      border-bottom: 2px solid #006400;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    h3 {
      margin-top: 20px;
      font-size: 16px;
      color: #555;
      border-left: 4px solid #006400;
      padding-left: 8px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      background: #fff;
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #f0f0f0;
      color: #333;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    .text-right {
      text-align: right;
    }

    .total-row {
      background-color: #e8ffe8;
      font-weight: bold;
    }

    .no-data {
      text-align: center;
      color: #888;
      padding: 40px 0;
      font-size: 16px;
    }

    td {
      word-wrap: break-word;
      max-width: 200px;
    }
  </style>
</head>
<body>

  <h1>Relatório de Divídas</h1>

  @if($nested->isEmpty())
    <p class="no-data">Nenhum registro encontrado.</p>
  @else
    @foreach($nested as $memberName => $periods)
      <h2 class="member-title">Membro: {{ $memberName }}</h2>
      @foreach($periods as $period => $movs)
        <h3>Período: {{ $period }}</h3>
        <table>
          <thead>
            <tr>
              <th>Valor</th>
              <th>Descrição</th>
              <th>Data de Pagamento</th>
            </tr>
          </thead>
          <tbody>
            @foreach($movs->sortBy('origin.payday') as $mov)
              <tr>
                <td class="text-right">
                  R$ {{ number_format($mov->value, 2, ',', '.') }}
                </td>
                <td>{{ $mov->description ?? '' }}</td>
                <td>{{ $mov->origin->payday ?? '' }}</td>
              </tr>
            @endforeach
            <tr class="total-row">
              <td class="text-right">
                R$ {{ number_format($movs->sum('value'), 2, ',', '.') }}
              </td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      @endforeach
    @endforeach
  @endif

</body>
</html>
